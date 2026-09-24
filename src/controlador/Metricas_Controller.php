<?php

use benjamin\plantillaweb\libs\Controlador;
use benjamin\plantillaweb\libs\Metrics;

class Metricas_Controller extends Controlador
{
    private const RANGOS_PERMITIDOS = [7, 14, 30, 90, 180, 365];

    public function index()
    {
        if (!Metrics::isEnabled()) {
            http_response_code(404);
            echo 'Modulo de metricas deshabilitado.';
            return;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';

            if (!Metrics::authenticate($password)) {
                $error = 'Contrasena incorrecta.';
            } else {
                header('Location: ?url=metricas/index');
                exit;
            }
        }

        if (!Metrics::isAuthenticated()) {
            $this->cargarVista('metricas/index', [
                'isAuthenticated' => false,
                'error' => $error,
                'summary' => null,
            ]);
            return;
        }

        $days = $this->resolverRango();
        $summary = Metrics::summarize($days);

        $this->cargarVista('metricas/index', [
            'isAuthenticated' => true,
            'error' => null,
            'summary' => $summary,
            'days' => $days,
        ]);
    }

    public function collect()
    {
        if (!Metrics::isEnabled()) {
            http_response_code(404);
            echo json_encode(['ok' => false]);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(Metrics::collectFromRequest(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function export()
    {
        if (!Metrics::isEnabled() || !Metrics::isAuthenticated()) {
            http_response_code(403);
            echo 'Acceso denegado.';
            return;
        }

        $days = $this->resolverRango();
        $summary = Metrics::summarize($days);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="metricas-' . $days . 'dias.csv"');

        $out = fopen('php://output', 'w');
        // BOM para que Excel abra el CSV con acentos correctos
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['Dia', 'Vistas', 'Visitantes unicos', 'Clicks', 'Clicks WhatsApp', 'Clicks telefono'], ';');

        foreach ($summary['daily'] as $day => $row) {
            fputcsv($out, [
                $day,
                (int)($row['pageviews'] ?? 0),
                (int)($row['unique_visitors'] ?? 0),
                (int)($row['clicks'] ?? 0),
                (int)($row['whatsapp_clicks'] ?? 0),
                (int)($row['phone_clicks'] ?? 0),
            ], ';');
        }

        fputcsv($out, [], ';');
        fputcsv($out, ['Paginas mas vistas', 'Vistas', 'Visitantes'], ';');
        foreach ($summary['pages'] as $path => $row) {
            fputcsv($out, [$path, (int)$row['views'], (int)$row['visitors']], ';');
        }

        fputcsv($out, [], ';');
        fputcsv($out, ['Fuente de trafico', 'Vistas'], ';');
        foreach ($summary['referrers'] as $label => $count) {
            fputcsv($out, [$label, (int)$count], ';');
        }

        fclose($out);
        exit;
    }

    public function logout()
    {
        Metrics::logout();
        header('Location: ?url=metricas/index');
        exit;
    }

    private function resolverRango(): int
    {
        $days = (int)($_GET['days'] ?? 30);
        return in_array($days, self::RANGOS_PERMITIDOS, true) ? $days : 30;
    }
}
