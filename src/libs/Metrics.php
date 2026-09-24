<?php

namespace benjamin\plantillaweb\libs;

class Metrics
{
    private const DISPLAY_TZ = 'America/Montevideo';

    public static function isEnabled(): bool
    {
        return defined('METRICAS_HABILITADAS') && METRICAS_HABILITADAS;
    }

    public static function dataDir(): string
    {
        return defined('METRICAS_DATA_DIR') ? METRICAS_DATA_DIR : __DIR__ . '/../../data/metrics';
    }

    public static function ensureStorage(): void
    {
        $dir = self::dataDir();

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $htaccess = $dir . '/.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n");
        }
    }

    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION[METRICAS_SESSION_KEY]);
    }

    public static function authenticate(string $password): bool
    {
        if (!defined('METRICAS_PASSWORD_HASH')) {
            return false;
        }

        $valid = password_verify($password, METRICAS_PASSWORD_HASH);

        if ($valid) {
            $_SESSION[METRICAS_SESSION_KEY] = true;
        }

        return $valid;
    }

    public static function logout(): void
    {
        unset($_SESSION[METRICAS_SESSION_KEY]);
    }

    public static function shouldIgnorePath(string $path): bool
    {
        return str_contains($path, 'metricas');
    }

    /**
     * Eventos generados en el entorno local (XAMPP). Se registran igual (asi se puede probar el panel),
     * salvo que METRICAS_IGNORAR_LOCALHOST este en true.
     */
    private static function isLocalEvent(array $event): bool
    {
        if (!(defined('METRICAS_IGNORAR_LOCALHOST') && METRICAS_IGNORAR_LOCALHOST)) {
            return false;
        }
        return !empty($event['local']);
    }

    private static function isLocalHost(): bool
    {
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
        return str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');
    }

    /** Prefijo de la app cuando corre en un subdirectorio (ej. /cerrajeromontevideo en XAMPP). Vacio en produccion. */
    private static function basePath(): string
    {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
        return $base === '/' ? '' : $base;
    }

    public static function appendEvent(array $event): void
    {
        self::ensureStorage();

        $event['recorded_at'] = gmdate('c');
        $line = json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($line === false) {
            return;
        }

        $file = self::dataDir() . '/' . gmdate('Y-m-d') . '.ndjson';
        file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public static function collectFromRequest(): array
    {
        $raw = file_get_contents('php://input');
        $payload = json_decode($raw ?: '{}', true);

        if (!is_array($payload)) {
            return ['ok' => false, 'error' => 'invalid_json'];
        }

        $type = self::sanitizeString($payload['type'] ?? '');
        $path = self::sanitizePath($payload['path'] ?? '/');

        if (!$type || !$path || self::shouldIgnorePath($path)) {
            return ['ok' => false, 'error' => 'ignored'];
        }
        if (self::isLocalHost() && defined('METRICAS_IGNORAR_LOCALHOST') && METRICAS_IGNORAR_LOCALHOST) {
            return ['ok' => false, 'error' => 'ignored_localhost'];
        }

        $event = [
            'type' => $type,
            'path' => $path,
            'local' => self::isLocalHost(),
            'title' => self::sanitizeString($payload['title'] ?? ''),
            'referrer' => self::sanitizeReferrer($payload['referrer'] ?? ''),
            'utm_source' => self::sanitizeString(is_string($payload['utm_source'] ?? null) ? $payload['utm_source'] : ''),
            'client_id' => self::sanitizeString($payload['client_id'] ?? ''),
            'session_id' => session_id(),
            'event_name' => self::sanitizeString($payload['event_name'] ?? ''),
            'href' => self::sanitizeString($payload['href'] ?? ''),
            'label' => self::sanitizeString($payload['label'] ?? ''),
            'language' => self::sanitizeString($payload['language'] ?? ''),
            'screen' => self::sanitizeString($payload['screen'] ?? ''),
            'timezone' => self::sanitizeString($payload['timezone'] ?? ''),
            'user_agent' => self::sanitizeString($_SERVER['HTTP_USER_AGENT'] ?? ''),
            'ip_hash' => hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|' . ($_SERVER['HTTP_USER_AGENT'] ?? '')),
        ];

        self::appendEvent($event);

        return ['ok' => true];
    }

    public static function summarize(int $days = 30): array
    {
        self::ensureStorage();

        $tz = new \DateTimeZone(self::DISPLAY_TZ);
        $days = max(1, $days);

        $end = new \DateTimeImmutable('today', $tz);
        $start = $end->modify('-' . ($days - 1) . ' days');
        $prevEnd = $start->modify('-1 day');
        $prevStart = $prevEnd->modify('-' . ($days - 1) . ' days');

        $startKey = $start->format('Y-m-d');
        $endKey = $end->format('Y-m-d');
        $prevStartKey = $prevStart->format('Y-m-d');
        $prevEndKey = $prevEnd->format('Y-m-d');

        $allEvents = self::readEvents($prevStartKey, $endKey, $tz);

        $current = [];
        $previousTotals = self::emptyTotals();

        $prevVisitors = [];
        $prevSessions = [];

        foreach ($allEvents as $event) {
            $day = $event['_local_day'];

            if ($day >= $startKey && $day <= $endKey) {
                $current[] = $event;
                continue;
            }

            if ($day >= $prevStartKey && $day <= $prevEndKey) {
                self::accumulateTotals($previousTotals, $event, $prevVisitors, $prevSessions);
            }
        }

        $previousTotals['unique_visitors'] = count($prevVisitors);
        $previousTotals['sessions'] = count($prevSessions);
        self::finishTotals($previousTotals);

        $summary = [
            'range' => [
                'start' => $startKey,
                'end' => $endKey,
                'days' => $days,
                'prev_start' => $prevStartKey,
                'prev_end' => $prevEndKey,
            ],
            'totals' => self::emptyTotals(),
            'previous' => $previousTotals,
            'deltas' => [],
            'daily' => [],
            'hourly' => array_fill(0, 24, 0),
            'weekday' => array_fill(0, 7, 0),
            'devices' => ['Celular' => 0, 'Computadora' => 0, 'Tablet' => 0],
            'browsers' => [],
            'pages' => [],
            'referrers' => [],
            'event_names' => [],
            'labels' => [],
            'recent' => [],
            'chart' => [],
            'insights' => [],
        ];

        $visitors = [];
        $sessions = [];
        $pageVisitors = [];

        foreach ($current as $event) {
            $day = $event['_local_day'];
            $type = $event['type'] ?? '';

            if (!isset($summary['daily'][$day])) {
                $summary['daily'][$day] = [
                    'pageviews' => 0,
                    'clicks' => 0,
                    'whatsapp_clicks' => 0,
                    'phone_clicks' => 0,
                    'visitors' => [],
                ];
            }

            $clientId = ($event['client_id'] ?? '') !== '' ? $event['client_id'] : ($event['ip_hash'] ?? '');
            if ($clientId) {
                $visitors[$clientId] = true;
                $summary['daily'][$day]['visitors'][$clientId] = true;
            }

            if (!empty($event['session_id'])) {
                $sessions[$event['session_id']] = true;
            }

            self::accumulateTotals($summary['totals'], $event, $visitors, $sessions);

            if ($type === 'pageview') {
                $summary['daily'][$day]['pageviews']++;
                $summary['hourly'][$event['_local_hour']]++;
                $summary['weekday'][$event['_local_dow']]++;

                $path = ($event['path'] ?? '') !== '' ? $event['path'] : '/';
                if (!isset($summary['pages'][$path])) {
                    $summary['pages'][$path] = ['views' => 0, 'visitors' => 0];
                    $pageVisitors[$path] = [];
                }
                $summary['pages'][$path]['views']++;
                if ($clientId) {
                    $pageVisitors[$path][$clientId] = true;
                }

                $referrer = self::eventSource($event);
                $summary['referrers'][$referrer] = ($summary['referrers'][$referrer] ?? 0) + 1;

                $device = self::detectDevice($event['user_agent'] ?? '');
                $summary['devices'][$device] = ($summary['devices'][$device] ?? 0) + 1;

                $browser = self::detectBrowser($event['user_agent'] ?? '');
                $summary['browsers'][$browser] = ($summary['browsers'][$browser] ?? 0) + 1;
            }

            if ($type === 'click') {
                $summary['daily'][$day]['clicks']++;

                $eventName = ($event['event_name'] ?? '') !== '' ? $event['event_name'] : 'click';
                $summary['event_names'][$eventName] = ($summary['event_names'][$eventName] ?? 0) + 1;

                $label = trim($event['label'] ?? '');
                if ($label !== '') {
                    $summary['labels'][$label] = ($summary['labels'][$label] ?? 0) + 1;
                }

                $href = $event['href'] ?? '';
                if (str_contains($href, 'wa.me') || str_contains($href, 'whatsapp')) {
                    $summary['daily'][$day]['whatsapp_clicks']++;
                }
                if (str_starts_with($href, 'tel:')) {
                    $summary['daily'][$day]['phone_clicks']++;
                }
            }
        }

        $summary['totals']['unique_visitors'] = count($visitors);
        $summary['totals']['sessions'] = count($sessions);
        self::finishTotals($summary['totals']);

        foreach ($summary['daily'] as $day => $row) {
            $summary['daily'][$day]['unique_visitors'] = count($row['visitors']);
            unset($summary['daily'][$day]['visitors']);
        }

        foreach ($summary['pages'] as $path => $row) {
            $summary['pages'][$path]['visitors'] = count($pageVisitors[$path] ?? []);
        }

        self::fillDailyRange($summary['daily'], $start, $end);
        ksort($summary['daily']);

        uasort($summary['pages'], static fn($a, $b) => $b['views'] <=> $a['views']);
        arsort($summary['referrers']);
        arsort($summary['browsers']);
        arsort($summary['event_names']);
        arsort($summary['labels']);

        $summary['pages'] = array_slice($summary['pages'], 0, 12, true);
        $summary['referrers'] = array_slice($summary['referrers'], 0, 10, true);
        $summary['browsers'] = array_slice($summary['browsers'], 0, 8, true);
        $summary['event_names'] = array_slice($summary['event_names'], 0, 10, true);
        $summary['labels'] = array_slice($summary['labels'], 0, 10, true);
        $summary['recent'] = array_slice(array_reverse($current), 0, 120);

        $summary['deltas'] = self::buildDeltas($summary['totals'], $previousTotals);
        $summary['chart'] = self::buildChartData($summary['daily']);
        $summary['insights'] = self::buildInsights($summary);

        return $summary;
    }

    private static function emptyTotals(): array
    {
        return [
            'pageviews' => 0,
            'unique_visitors' => 0,
            'sessions' => 0,
            'clicks' => 0,
            'whatsapp_clicks' => 0,
            'phone_clicks' => 0,
            'contact_clicks' => 0,
            'contact_rate' => 0.0,
            'pages_per_session' => 0.0,
        ];
    }

    private static function accumulateTotals(array &$totals, array $event, array &$visitors, array &$sessions): void
    {
        $type = $event['type'] ?? '';

        $clientId = ($event['client_id'] ?? '') !== '' ? $event['client_id'] : ($event['ip_hash'] ?? '');
        if ($clientId) {
            $visitors[$clientId] = true;
        }
        if (!empty($event['session_id'])) {
            $sessions[$event['session_id']] = true;
        }

        if ($type === 'pageview') {
            $totals['pageviews']++;
        }

        if ($type === 'click') {
            $totals['clicks']++;

            $href = $event['href'] ?? '';
            if (str_contains($href, 'wa.me') || str_contains($href, 'whatsapp')) {
                $totals['whatsapp_clicks']++;
            }
            if (str_starts_with($href, 'tel:')) {
                $totals['phone_clicks']++;
            }
        }
    }

    private static function finishTotals(array &$totals): void
    {
        $totals['contact_clicks'] = $totals['whatsapp_clicks'] + $totals['phone_clicks'];
        $totals['contact_rate'] = $totals['unique_visitors'] > 0
            ? round(100 * $totals['contact_clicks'] / $totals['unique_visitors'], 1)
            : 0.0;
        $totals['pages_per_session'] = $totals['sessions'] > 0
            ? round($totals['pageviews'] / $totals['sessions'], 1)
            : 0.0;
    }

    private static function buildDeltas(array $current, array $previous): array
    {
        $deltas = [];
        $keys = ['pageviews', 'unique_visitors', 'sessions', 'whatsapp_clicks', 'phone_clicks', 'contact_clicks', 'contact_rate'];

        foreach ($keys as $key) {
            $now = (float)($current[$key] ?? 0);
            $before = (float)($previous[$key] ?? 0);

            if ($before <= 0) {
                $deltas[$key] = $now > 0 ? null : 0.0;
                continue;
            }

            $deltas[$key] = round(100 * ($now - $before) / $before, 1);
        }

        return $deltas;
    }

    private static function fillDailyRange(array &$daily, \DateTimeImmutable $start, \DateTimeImmutable $end): void
    {
        for ($date = $start; $date <= $end; $date = $date->modify('+1 day')) {
            $key = $date->format('Y-m-d');

            if (!isset($daily[$key])) {
                $daily[$key] = [
                    'pageviews' => 0,
                    'clicks' => 0,
                    'whatsapp_clicks' => 0,
                    'phone_clicks' => 0,
                    'unique_visitors' => 0,
                ];
            }
        }
    }

    private static function buildChartData(array $daily): array
    {
        $labels = [];
        $pageviews = [];
        $visitors = [];
        $clicks = [];
        $whatsapp = [];

        foreach ($daily as $day => $row) {
            $labels[] = $day;
            $pageviews[] = (int)($row['pageviews'] ?? 0);
            $visitors[] = (int)($row['unique_visitors'] ?? 0);
            $clicks[] = (int)($row['clicks'] ?? 0);
            $whatsapp[] = (int)($row['whatsapp_clicks'] ?? 0);
        }

        return [
            'labels' => $labels,
            'pageviews' => $pageviews,
            'visitors' => $visitors,
            'clicks' => $clicks,
            'whatsapp' => $whatsapp,
            'max' => max([1, ...$pageviews, ...$visitors, ...$clicks, ...$whatsapp]),
        ];
    }

    private static function buildInsights(array $summary): array
    {
        $daily = $summary['daily'];

        $peakDay = '';
        $peakPageviews = 0;
        $totalPageviews = 0;
        $activeDays = 0;

        foreach ($daily as $day => $row) {
            $pageviews = (int)($row['pageviews'] ?? 0);
            $totalPageviews += $pageviews;

            if ($pageviews > 0) {
                $activeDays++;
            }

            if ($pageviews > $peakPageviews) {
                $peakDay = $day;
                $peakPageviews = $pageviews;
            }
        }

        $daysCount = max(1, count($daily));

        $bestHour = null;
        if (array_sum($summary['hourly']) > 0) {
            $bestHour = (int)array_search(max($summary['hourly']), $summary['hourly'], true);
        }

        $bestWeekday = null;
        if (array_sum($summary['weekday']) > 0) {
            $bestWeekday = (int)array_search(max($summary['weekday']), $summary['weekday'], true);
        }

        $topPage = null;
        foreach ($summary['pages'] as $path => $row) {
            $topPage = ['path' => $path, 'views' => $row['views']];
            break;
        }

        return [
            'peak_day' => $peakDay,
            'peak_pageviews' => $peakPageviews,
            'average_pageviews' => round($totalPageviews / $daysCount, 1),
            'active_days' => $activeDays,
            'best_hour' => $bestHour,
            'best_weekday' => $bestWeekday,
            'top_page' => $topPage,
        ];
    }

    /**
     * Lee eventos entre dos dias locales (inclusive) y les agrega
     * _local_day / _local_hour / _local_dow ya convertidos a hora local.
     */
    private static function readEvents(string $startKey, string $endKey, \DateTimeZone $tz): array
    {
        $events = [];
        $files = glob(self::dataDir() . '/*.ndjson') ?: [];
        $utc = new \DateTimeZone('UTC');

        // Los archivos se nombran por dia UTC; un dia extra a cada lado cubre el desfase horario.
        $minFile = date('Y-m-d', strtotime($startKey . ' -1 day'));
        $maxFile = date('Y-m-d', strtotime($endKey . ' +1 day'));

        foreach ($files as $file) {
            $name = basename($file, '.ndjson');
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $name) || $name < $minFile || $name > $maxFile) {
                continue;
            }

            $handle = fopen($file, 'rb');
            if (!$handle) {
                continue;
            }

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }

                $decoded = json_decode($line, true);
                if (!is_array($decoded) || empty($decoded['recorded_at'])) {
                    continue;
                }

                try {
                    $when = new \DateTimeImmutable($decoded['recorded_at'], $utc);
                } catch (\Exception $e) {
                    continue;
                }

                $local = $when->setTimezone($tz);
                $day = $local->format('Y-m-d');

                if ($day < $startKey || $day > $endKey) {
                    continue;
                }

                if (self::isBot($decoded['user_agent'] ?? '') || self::isLocalEvent($decoded)) {
                    continue;
                }

                $decoded['_local_day'] = $day;
                $decoded['_local_hour'] = (int)$local->format('G');
                $decoded['_local_dow'] = ((int)$local->format('N')) - 1; // 0 = lunes
                $decoded['_local_time'] = $local->format('d/m H:i');

                $events[] = $decoded;
            }

            fclose($handle);
        }

        usort($events, static function ($a, $b) {
            return strcmp($a['recorded_at'] ?? '', $b['recorded_at'] ?? '');
        });

        return $events;
    }

    private static function isBot(string $userAgent): bool
    {
        return $userAgent !== '' && preg_match('/bot|crawl|spider|slurp|headless|lighthouse|pingdom|facebookexternalhit/i', $userAgent) === 1;
    }

    private static function detectDevice(string $userAgent): string
    {
        if (preg_match('/ipad|tablet|(android(?!.*mobile))/i', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/mobi|iphone|android|ipod/i', $userAgent)) {
            return 'Celular';
        }

        return 'Computadora';
    }

    private static function detectBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Edg/') || str_contains($userAgent, 'EdgA/')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'SamsungBrowser')) {
            return 'Samsung Internet';
        }
        if (str_contains($userAgent, 'OPR/') || str_contains($userAgent, 'Opera')) {
            return 'Opera';
        }
        if (str_contains($userAgent, 'Firefox/')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'CriOS/')) {
            return 'Chrome (iOS)';
        }
        if (str_contains($userAgent, 'Chrome/')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Safari/')) {
            return 'Safari';
        }

        return $userAgent === '' ? 'Desconocido' : 'Otro';
    }

    public static function eventSource(array $event): string
    {
        $source = trim(is_string($event['utm_source'] ?? null) ? $event['utm_source'] : '');
        return $source !== '' ? $source : self::normalizeReferrer($event['referrer'] ?? '');
    }

    public static function normalizeReferrer(string $referrer): string
    {
        if ($referrer === '') {
            return 'Directo / sin referrer';
        }

        $host = parse_url($referrer, PHP_URL_HOST);
        if (!$host) {
            return 'Directo / sin referrer';
        }

        $host = preg_replace('/^www\./', '', rtrim(strtolower($host), '.'));

        $known = [
            'google.com' => 'Google',
            'bing.com' => 'Bing',
            'yahoo.com' => 'Yahoo',
            'duckduckgo.com' => 'DuckDuckGo',
            'instagram.com' => 'Instagram',
            'facebook.com' => 'Facebook',
            'fb.com' => 'Facebook',
            't.co' => 'X / Twitter',
            'twitter.com' => 'X / Twitter',
            'x.com' => 'X / Twitter',
            'linkedin.com' => 'LinkedIn',
            'youtube.com' => 'YouTube',
            'youtu.be' => 'YouTube',
            'whatsapp.com' => 'WhatsApp',
            'wa.me' => 'WhatsApp',
            'mercadolibre.com' => 'Mercado Libre',
            'chatgpt.com' => 'ChatGPT',
            'chat.openai.com' => 'ChatGPT',
        ];

        foreach ($known as $needle => $label) {
            if ($host === $needle || str_ends_with($host, '.' . $needle)) {
                return $label;
            }
        }

        // Dominios regionales, respetando los limites de cada componente.
        if (preg_match('/(?:^|\.)(google|yahoo|mercadolibre)\.(?:[a-z]{2}|com\.[a-z]{2}|co\.[a-z]{2})$/', $host, $match)) {
            return ['google' => 'Google', 'yahoo' => 'Yahoo', 'mercadolibre' => 'Mercado Libre'][$match[1]];
        }

        return $host;
    }

    private static function sanitizeString(string $value): string
    {
        $value = trim($value);
        return mb_substr($value, 0, 255);
    }

    private static function sanitizePath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '/';
        }

        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        // En XAMPP la app vive en /cerrajeromontevideo/...: se guarda la ruta como en produccion (/...)
        $base = self::basePath();
        if ($base !== '' && str_starts_with($path, $base . '/')) {
            $path = substr($path, strlen($base));
        } elseif ($base !== '' && $path === $base) {
            $path = '/';
        }
        return mb_substr($path, 0, 255);
    }

    private static function sanitizeReferrer(string $referrer): string
    {
        $referrer = trim($referrer);
        return mb_substr($referrer, 0, 500);
    }
}
