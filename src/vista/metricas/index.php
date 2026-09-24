<?php
$isAuthenticated = $isAuthenticated ?? false;
$error = $error ?? null;
$summary = $summary ?? [];
$days = $days ?? 30;

$totals   = $summary['totals']   ?? [];
$previous = $summary['previous'] ?? [];
$deltas   = $summary['deltas']   ?? [];
$chart    = $summary['chart']    ?? [];
$insights = $summary['insights'] ?? [];
$range    = $summary['range']    ?? [];
$hourly   = $summary['hourly']   ?? array_fill(0, 24, 0);
$weekday  = $summary['weekday']  ?? array_fill(0, 7, 0);
$devices  = $summary['devices']  ?? [];
$browsers = $summary['browsers'] ?? [];

$diasSemana      = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
$diasSemanaLargo = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
$rangosDias      = [7 => '7d', 14 => '14d', 30 => '30d', 90 => '90d', 180 => '180d', 365 => '1 año'];

$fmtNum = static fn($n) => number_format((float)$n, 0, ',', '.');

$formatDay = static function (string $day, string $formato = 'd/m'): string {
    $ts = strtotime($day);
    return $ts ? date($formato, $ts) : $day;
};

$dayWithWeekday = static function (string $day) use ($diasSemana): string {
    $ts = strtotime($day);
    if (!$ts) return $day;
    return $diasSemana[((int)date('N', $ts)) - 1] . ' ' . date('d/m', $ts);
};

// Chip de variacion vs periodo anterior: null = no habia datos previos
$deltaChip = static function (?float $delta, bool $invertColors = false): string {
    if ($delta === null) {
        return '<span class="metrics-delta metrics-delta--new" title="Sin datos en el periodo anterior">Nuevo</span>';
    }
    if (abs($delta) < 0.05) {
        return '<span class="metrics-delta metrics-delta--flat">= igual</span>';
    }
    $up = $delta > 0;
    $good = $invertColors ? !$up : $up;
    $cls = $good ? 'metrics-delta--up' : 'metrics-delta--down';
    $arrow = $up ? '&#9650;' : '&#9660;';
    $val = number_format(abs($delta), 1, ',', '.');
    return '<span class="metrics-delta ' . $cls . '" title="Comparado con el periodo anterior">' . $arrow . ' ' . $val . '%</span>';
};

$detectarDispositivo = static function (string $ua): string {
    if (preg_match('/ipad|tablet|(android(?!.*mobile))/i', $ua)) return 'Tablet';
    if (preg_match('/mobi|iphone|android|ipod/i', $ua)) return 'Celular';
    return 'PC';
};

$chartLabels    = array_map($formatDay, $chart['labels'] ?? []);
$chartPageviews = array_map('intval', $chart['pageviews'] ?? []);
$chartVisitors  = array_map('intval', $chart['visitors']  ?? []);
$chartClicks    = array_map('intval', $chart['clicks']    ?? []);
$chartWhatsapp  = array_map('intval', $chart['whatsapp']  ?? []);

// Etiquetas completas para el tooltip e indices de fin de semana para sombrear
$chartFullLabels = [];
$chartWeekends = [];
foreach (($chart['labels'] ?? []) as $i => $d) {
    $ts = strtotime($d);
    $dow = $ts ? (int)date('N', $ts) : 0;
    $chartFullLabels[] = $ts ? ($diasSemana[$dow - 1] . ' ' . date('d/m/Y', $ts)) : $d;
    if ($dow >= 6) {
        $chartWeekends[] = $i;
    }
}

$hayDatos = ((int)($totals['pageviews'] ?? 0) + (int)($totals['clicks'] ?? 0)) > 0;

$totalDevices = max(1, array_sum($devices));
$maxWeekday = max(1, max($weekday ?: [0]));
?>
<!doctype html>
<html lang="es-UY">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $metricsSitio = defined('EMPRESA_NOMBRE') ? EMPRESA_NOMBRE : 'Sitio'; ?>
  <title>Metricas privadas | <?= htmlspecialchars($metricsSitio) ?></title>
  <meta name="robots" content="noindex,nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Host+Grotesk:wght@300..800&display=swap">
  <link rel="stylesheet" href="<?= $ruta ?>/css/metrics.css?v=<?= @filemtime('public/css/metrics.css') ?: 2 ?>">
  <?php if ($isAuthenticated): ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <?php endif; ?>
</head>
<body class="metrics-body">
  <main class="metrics-shell">
    <?php if (!$isAuthenticated): ?>
      <section class="metrics-login">
        <div class="metrics-login__card">
          <p class="metrics-login__eyebrow">Acceso privado</p>
          <h1>Panel de metricas de <?= htmlspecialchars($metricsSitio) ?></h1>
          <p>Ingresa la contrasena para ver visitas, clicks, paginas vistas y fuentes de trafico. Los datos se guardan en archivos locales, sin servicios de terceros.</p>

          <?php if ($error): ?>
            <div class="metrics-alert" role="alert"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="post" action="?url=metricas/index" class="metrics-login__form">
            <label for="password">Contrasena</label>
            <div class="metrics-login__field">
              <input id="password" name="password" type="password" required autocomplete="current-password" autofocus>
              <button type="button" class="metrics-login__toggle" id="togglePassword" aria-label="Mostrar contrasena">&#128065;</button>
            </div>
            <button type="submit">Entrar al panel</button>
          </form>
        </div>
      </section>

      <script>
      (function () {
        var btn = document.getElementById('togglePassword');
        var input = document.getElementById('password');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
          var show = input.type === 'password';
          input.type = show ? 'text' : 'password';
          btn.setAttribute('aria-label', show ? 'Ocultar contrasena' : 'Mostrar contrasena');
          input.focus();
        });
      })();
      </script>
    <?php else: ?>
      <section class="metrics-dashboard">

        <header class="metrics-header">
          <div class="metrics-header__title">
            <p class="metrics-login__eyebrow">Metricas locales &middot; sin terceros</p>
            <h1>Panel privado</h1>
            <p>
              <?= htmlspecialchars($formatDay($range['start'] ?? '', 'd/m/Y')) ?>
              &rarr;
              <?= htmlspecialchars($formatDay($range['end'] ?? '', 'd/m/Y')) ?>
              &middot; comparado con los <?= (int)$days ?> dias anteriores
            </p>
          </div>

          <div class="metrics-header__actions">
            <nav class="metrics-range" aria-label="Rango de dias">
              <?php foreach ($rangosDias as $d => $label): ?>
                <a href="?url=metricas/index&amp;days=<?= $d ?>"
                   class="metrics-range__btn <?= $days === $d ? 'is-active' : '' ?>"
                   <?= $days === $d ? 'aria-current="page"' : '' ?>><?= $label ?></a>
              <?php endforeach; ?>
            </nav>

            <div class="metrics-header__buttons">
              <a href="?url=metricas/export&amp;days=<?= (int)$days ?>" class="metrics-btn metrics-btn--ghost" title="Descargar resumen en CSV">&#8681; CSV</a>
              <a href="?url=metricas/index&amp;days=<?= (int)$days ?>" class="metrics-btn metrics-btn--ghost" title="Recargar datos">&#8635; Actualizar</a>
              <a href="?url=metricas/logout" class="metrics-btn metrics-btn--solid">Salir</a>
            </div>
          </div>
        </header>

        <?php if (!$hayDatos): ?>
          <section class="metrics-card metrics-empty">
            <div class="metrics-empty__icon">&#128202;</div>
            <h2>Sin datos en este rango</h2>
            <p>Todavia no se registraron visitas ni clicks entre el <?= htmlspecialchars($formatDay($range['start'] ?? '', 'd/m/Y')) ?> y el <?= htmlspecialchars($formatDay($range['end'] ?? '', 'd/m/Y')) ?>. Proba con un rango mas amplio.</p>
          </section>
        <?php endif; ?>

        <section class="metrics-grid metrics-grid--stats">
          <article class="metrics-stat metrics-stat--views">
            <span>Paginas vistas</span>
            <strong><?= $fmtNum($totals['pageviews'] ?? 0) ?></strong>
            <?= $deltaChip($deltas['pageviews'] ?? 0.0) ?>
          </article>
          <article class="metrics-stat metrics-stat--visitors">
            <span>Visitantes unicos</span>
            <strong><?= $fmtNum($totals['unique_visitors'] ?? 0) ?></strong>
            <?= $deltaChip($deltas['unique_visitors'] ?? 0.0) ?>
          </article>
          <article class="metrics-stat metrics-stat--sessions">
            <span>Sesiones</span>
            <strong><?= $fmtNum($totals['sessions'] ?? 0) ?></strong>
            <small><?= number_format((float)($totals['pages_per_session'] ?? 0), 1, ',', '.') ?> paginas por sesion</small>
          </article>
          <article class="metrics-stat metrics-stat--whatsapp">
            <span>Clicks a WhatsApp</span>
            <strong><?= $fmtNum($totals['whatsapp_clicks'] ?? 0) ?></strong>
            <?= $deltaChip($deltas['whatsapp_clicks'] ?? 0.0) ?>
          </article>
          <article class="metrics-stat metrics-stat--phone">
            <span>Clicks al telefono</span>
            <strong><?= $fmtNum($totals['phone_clicks'] ?? 0) ?></strong>
            <?= $deltaChip($deltas['phone_clicks'] ?? 0.0) ?>
          </article>
          <article class="metrics-stat metrics-stat--rate">
            <span>Tasa de contacto</span>
            <strong><?= number_format((float)($totals['contact_rate'] ?? 0), 1, ',', '.') ?>%</strong>
            <small><?= $fmtNum($totals['contact_clicks'] ?? 0) ?> clicks de contacto / <?= $fmtNum($totals['unique_visitors'] ?? 0) ?> visitantes</small>
          </article>
        </section>

        <section class="metrics-card metrics-card--wide">
          <div class="metrics-card__header">
            <div>
              <h2>Avance diario</h2>
              <p>Toca las series para mostrarlas u ocultarlas. Los fines de semana aparecen sombreados y la linea punteada marca el promedio diario.</p>
            </div>
            <div class="metrics-chart__controls">
              <div class="metrics-chart__legend" id="chartLegend">
                <button type="button" data-ds="0" class="is-on"><i class="metrics-chart__dot metrics-chart__dot--views"></i>Vistas</button>
                <button type="button" data-ds="1" class="is-on"><i class="metrics-chart__dot metrics-chart__dot--visitors"></i>Visitantes</button>
                <button type="button" data-ds="2" class="is-on"><i class="metrics-chart__dot metrics-chart__dot--clicks"></i>Clicks</button>
                <button type="button" data-ds="3" class="is-on"><i class="metrics-chart__dot metrics-chart__dot--whatsapp"></i>WhatsApp</button>
              </div>
              <div class="metrics-toggle" role="group" aria-label="Tipo de grafico">
                <button type="button" id="chartTypeLine" class="is-active">Linea</button>
                <button type="button" id="chartTypeBar">Barras</button>
              </div>
            </div>
          </div>

          <div class="metrics-insights">
            <article class="metrics-insights__item metrics-insights__item--views">
              <span>Pico de vistas</span>
              <strong><?= $fmtNum($insights['peak_pageviews'] ?? 0) ?></strong>
              <small><?= ($insights['peak_day'] ?? '') !== '' ? htmlspecialchars($dayWithWeekday($insights['peak_day'])) : 'sin datos' ?></small>
            </article>
            <article class="metrics-insights__item metrics-insights__item--neutral">
              <span>Promedio diario</span>
              <strong><?= number_format((float)($insights['average_pageviews'] ?? 0), 1, ',', '.') ?></strong>
              <small>vistas por dia</small>
            </article>
            <article class="metrics-insights__item metrics-insights__item--neutral">
              <span>Mejor horario</span>
              <strong><?= $insights['best_hour'] !== null ? sprintf('%02d:00', (int)$insights['best_hour']) : '&mdash;' ?></strong>
              <small>hora con mas visitas</small>
            </article>
            <article class="metrics-insights__item metrics-insights__item--clicks">
              <span>Mejor dia</span>
              <strong class="metrics-insights__mediumtext"><?= $insights['best_weekday'] !== null ? ucfirst($diasSemanaLargo[(int)$insights['best_weekday']]) : '&mdash;' ?></strong>
              <small><?= (int)($insights['active_days'] ?? 0) ?> dias con actividad</small>
            </article>
          </div>

          <div class="metrics-chart">
            <div class="metrics-chart__wrap">
              <canvas id="metricsChart" role="img" aria-label="Grafico de vistas, visitantes y clicks por dia"></canvas>
            </div>
          </div>
        </section>

        <section class="metrics-grid metrics-grid--thirds">
          <article class="metrics-card">
            <h2>Horarios de visita</h2>
            <p class="metrics-card__sub">Vistas por hora del dia (hora de Uruguay).</p>
            <div class="metrics-chart__wrap metrics-chart__wrap--small">
              <canvas id="hourlyChart" role="img" aria-label="Vistas por hora del dia"></canvas>
            </div>
          </article>

          <article class="metrics-card">
            <h2>Dispositivos</h2>
            <p class="metrics-card__sub">Con que navegan tus visitantes.</p>
            <div class="metrics-devices">
              <div class="metrics-chart__wrap metrics-chart__wrap--donut">
                <canvas id="devicesChart" role="img" aria-label="Distribucion por dispositivo"></canvas>
              </div>
              <ul class="metrics-devices__list">
                <?php $deviceColors = ['Celular' => '#54c6ff', 'Computadora' => '#ff5a60', 'Tablet' => '#f6c85f']; ?>
                <?php foreach ($devices as $name => $count): ?>
                  <li>
                    <i style="background: <?= $deviceColors[$name] ?? '#888' ?>"></i>
                    <span><?= htmlspecialchars($name) ?></span>
                    <strong><?= round(100 * $count / $totalDevices) ?>%</strong>
                    <em><?= $fmtNum($count) ?></em>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </article>

          <article class="metrics-card">
            <h2>Dias de la semana</h2>
            <p class="metrics-card__sub">Vistas acumuladas por dia de semana.</p>
            <ul class="metrics-weekdays">
              <?php foreach ($weekday as $i => $count): ?>
                <li>
                  <span class="metrics-weekdays__name"><?= $diasSemana[$i] ?></span>
                  <span class="metrics-weekdays__bar"><i style="width: <?= round(100 * $count / $maxWeekday) ?>%"></i></span>
                  <strong><?= $fmtNum($count) ?></strong>
                </li>
              <?php endforeach; ?>
            </ul>
          </article>
        </section>

        <section class="metrics-grid">
          <article class="metrics-card">
            <h2>Paginas mas vistas</h2>
            <ul class="metrics-list metrics-list--views">
              <?php if (!empty($summary['pages'])): ?>
                <?php $pMax = max(array_map(static fn($r) => $r['views'], $summary['pages'])); ?>
                <?php foreach ($summary['pages'] as $label => $row): ?>
                  <?php $pct = $pMax > 0 ? round(100 * $row['views'] / $pMax) : 0; ?>
                  <li style="--bar-pct:<?= $pct ?>%">
                    <span class="metrics-list__label" title="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></span>
                    <span class="metrics-list__extra"><?= $fmtNum($row['visitors']) ?> visit.</span>
                    <strong><?= $fmtNum($row['views']) ?></strong>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li style="--bar-pct:0%"><span class="metrics-list__label">Sin datos</span><strong>0</strong></li>
              <?php endif; ?>
            </ul>
          </article>

          <article class="metrics-card">
            <h2>Fuentes de trafico</h2>
            <ul class="metrics-list metrics-list--visitors">
              <?php if (!empty($summary['referrers'])): ?>
                <?php $rMax = max(array_values($summary['referrers'])); $rTotal = max(1, array_sum($summary['referrers'])); ?>
                <?php foreach ($summary['referrers'] as $label => $count): ?>
                  <?php $pct = $rMax > 0 ? round(100 * $count / $rMax) : 0; ?>
                  <li style="--bar-pct:<?= $pct ?>%">
                    <span class="metrics-list__label"><?= htmlspecialchars($label) ?></span>
                    <span class="metrics-list__extra"><?= round(100 * $count / $rTotal) ?>%</span>
                    <strong><?= $fmtNum($count) ?></strong>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li style="--bar-pct:0%"><span class="metrics-list__label">Directo / sin referrer</span><strong>0</strong></li>
              <?php endif; ?>
            </ul>
          </article>

          <article class="metrics-card">
            <h2>Navegadores</h2>
            <ul class="metrics-list metrics-list--clicks">
              <?php if (!empty($browsers)): ?>
                <?php $bMax = max(array_values($browsers)); ?>
                <?php foreach ($browsers as $label => $count): ?>
                  <?php $pct = $bMax > 0 ? round(100 * $count / $bMax) : 0; ?>
                  <li style="--bar-pct:<?= $pct ?>%">
                    <span class="metrics-list__label"><?= htmlspecialchars($label) ?></span>
                    <strong><?= $fmtNum($count) ?></strong>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li style="--bar-pct:0%"><span class="metrics-list__label">Sin datos</span><strong>0</strong></li>
              <?php endif; ?>
            </ul>
          </article>

          <article class="metrics-card">
            <h2>Botones mas usados</h2>
            <ul class="metrics-list metrics-list--whatsapp">
              <?php if (!empty($summary['labels'])): ?>
                <?php $lMax = max(array_values($summary['labels'])); ?>
                <?php foreach ($summary['labels'] as $label => $count): ?>
                  <?php $pct = $lMax > 0 ? round(100 * $count / $lMax) : 0; ?>
                  <li style="--bar-pct:<?= $pct ?>%">
                    <span class="metrics-list__label" title="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></span>
                    <strong><?= $fmtNum($count) ?></strong>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li style="--bar-pct:0%"><span class="metrics-list__label">Sin clicks registrados</span><strong>0</strong></li>
              <?php endif; ?>
            </ul>
          </article>
        </section>

        <section class="metrics-card metrics-card--wide">
          <h2>Actividad diaria</h2>
          <div class="metrics-table-wrap">
            <table class="metrics-table">
              <thead>
                <tr>
                  <th>Dia</th>
                  <th>Vistas</th>
                  <th>Visitantes</th>
                  <th>Clicks</th>
                  <th>WhatsApp</th>
                  <th>Telefono</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($summary['daily'])): ?>
                  <?php foreach (array_reverse($summary['daily'], true) as $day => $row): ?>
                    <?php $sinActividad = ((int)$row['pageviews'] + (int)$row['clicks']) === 0; ?>
                    <tr class="<?= $sinActividad ? 'metrics-table__row--muted' : '' ?>">
                      <td><?= htmlspecialchars($dayWithWeekday($day)) ?></td>
                      <td><span class="metrics-badge metrics-badge--views"><?= (int)$row['pageviews'] ?></span></td>
                      <td><span class="metrics-badge metrics-badge--visitors"><?= (int)$row['unique_visitors'] ?></span></td>
                      <td><span class="metrics-badge metrics-badge--clicks"><?= (int)$row['clicks'] ?></span></td>
                      <td><span class="metrics-badge metrics-badge--whatsapp"><?= (int)($row['whatsapp_clicks'] ?? 0) ?></span></td>
                      <td><span class="metrics-badge metrics-badge--phone"><?= (int)($row['phone_clicks'] ?? 0) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="metrics-table__empty">Todavia no hay datos registrados.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <section class="metrics-card metrics-card--wide">
          <div class="metrics-card__header">
            <div>
              <h2>Ultimos eventos</h2>
              <p>Los <?= count($summary['recent'] ?? []) ?> movimientos mas recientes del rango.</p>
            </div>
            <div class="metrics-events__controls">
              <div class="metrics-toggle" role="group" aria-label="Filtrar por tipo">
                <button type="button" data-filter="all" class="is-active">Todos</button>
                <button type="button" data-filter="pageview">Vistas</button>
                <button type="button" data-filter="click">Clicks</button>
              </div>
              <input type="search" id="eventSearch" class="metrics-search" placeholder="Buscar pagina, origen, boton o destino..." aria-label="Buscar en eventos">
            </div>
          </div>

          <div class="metrics-table-wrap">
            <table class="metrics-table" id="eventsTable">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Pagina</th>
                  <th>Origen</th>
                  <th>Evento</th>
                  <th>Dispositivo</th>
                  <th>Destino</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($summary['recent'])): ?>
                  <?php foreach ($summary['recent'] as $event): ?>
                    <?php
                      $tipo = $event['type'] ?? '';
                      $referencia = $event['referrer'] ?? '';
                      $origen = \benjamin\plantillaweb\libs\Metrics::eventSource($event);
                      if ($origen === 'Directo / sin referrer') {
                          $origen = 'Directo / origen no informado';
                      }
                      $detalleOrigen = trim($event['utm_source'] ?? '') !== ''
                          ? 'utm_source: ' . $event['utm_source'] . ($referencia !== '' ? ' | Referencia: ' . $referencia : '')
                          : ($referencia ?: $origen);
                      $texto = strtolower(($event['path'] ?? '') . ' ' . $origen . ' ' . $referencia . ' ' . ($event['event_name'] ?? '') . ' ' . ($event['label'] ?? '') . ' ' . ($event['href'] ?? ''));
                    ?>
                    <tr data-type="<?= htmlspecialchars($tipo) ?>" data-search="<?= htmlspecialchars($texto) ?>">
                      <td class="metrics-table__nowrap"><?= htmlspecialchars($event['_local_time'] ?? substr($event['recorded_at'] ?? '', 0, 16)) ?></td>
                      <td><span class="metrics-type-badge metrics-type-badge--<?= $tipo === 'pageview' ? 'view' : 'click' ?>"><?= $tipo === 'pageview' ? 'vista' : 'click' ?></span></td>
                      <td class="metrics-table__truncate" title="<?= htmlspecialchars($event['path'] ?? '') ?>"><?= htmlspecialchars($event['path'] ?? '') ?></td>
                      <td class="metrics-table__truncate" title="<?= htmlspecialchars($detalleOrigen) ?>"><?= htmlspecialchars($origen) ?></td>
                      <td class="metrics-table__truncate"><?= ($event['event_name'] ?? '') !== 'pageview' ? htmlspecialchars(($event['label'] ?? '') ?: ($event['event_name'] ?? '')) : '&mdash;' ?></td>
                      <td><?= htmlspecialchars($detectarDispositivo($event['user_agent'] ?? '')) ?></td>
                      <td class="metrics-table__truncate" title="<?= htmlspecialchars($event['href'] ?? '') ?>"><?= htmlspecialchars($event['href'] ?? '') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" class="metrics-table__empty">Todavia no hay eventos registrados.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="metrics-events__footer">
            <p id="eventsCount" class="metrics-events__count"></p>
            <button type="button" id="eventsMore" class="metrics-btn metrics-btn--ghost">Mostrar mas</button>
          </div>
        </section>
      </section>

      <script>
      (function () {
        if (typeof Chart === 'undefined') return;

        var COLORS = {
          views:    { rgb: '255, 90, 96',  hex: '#ff5a60' },
          visitors: { rgb: '84, 198, 255', hex: '#54c6ff' },
          clicks:   { rgb: '246, 200, 95', hex: '#f6c85f' },
          whatsapp: { rgb: '37, 211, 102', hex: '#25d366' }
        };

        Chart.defaults.font.family = 'Host Grotesk, sans-serif';
        Chart.defaults.color = 'rgba(255,255,255,0.38)';

        var tooltipStyle = {
          backgroundColor: 'rgba(11, 13, 17, 0.96)',
          borderColor: 'rgba(255,255,255,0.12)',
          borderWidth: 1,
          padding: 12,
          titleColor: 'rgba(255,255,255,0.75)',
          titleFont: { size: 12, weight: '600' },
          bodyColor: 'rgba(255,255,255,0.7)',
          bodyFont: { size: 13 },
          bodySpacing: 6,
          usePointStyle: true,
          pointStyle: 'circle'
        };

        // ── Grafico principal ──────────────────────────
        var canvas = document.getElementById('metricsChart');
        var mainChart = null;

        if (canvas) {
          var ctx = canvas.getContext('2d');
          var h = canvas.parentElement.clientHeight || 340;

          var FULL_LABELS = <?= json_encode($chartFullLabels) ?>;
          var WEEKENDS = <?= json_encode($chartWeekends) ?>;
          var AVG_VIEWS = <?= json_encode((float)($insights['average_pageviews'] ?? 0)) ?>;
          var AVG_LABEL = <?= json_encode('Promedio ' . number_format((float)($insights['average_pageviews'] ?? 0), 1, ',', '.')) ?>;

          var makeGradient = function (rgb, a0) {
            var grad = ctx.createLinearGradient(0, 0, 0, h);
            grad.addColorStop(0, 'rgba(' + rgb + ',' + a0 + ')');
            grad.addColorStop(0.55, 'rgba(' + rgb + ',' + (a0 * 0.3) + ')');
            grad.addColorStop(1, 'rgba(' + rgb + ',0)');
            return grad;
          };

          var makeDataset = function (label, data, color, fillA) {
            return {
              label: label,
              data: data,
              borderColor: color.hex,
              backgroundColor: makeGradient(color.rgb, fillA),
              fill: true,
              cubicInterpolationMode: 'monotone',
              borderCapStyle: 'round',
              borderJoinStyle: 'round',
              pointRadius: 0,
              pointHitRadius: 12,
              pointHoverRadius: 5,
              pointHoverBackgroundColor: color.hex,
              pointHoverBorderColor: 'rgba(255,255,255,0.95)',
              pointHoverBorderWidth: 2,
              borderWidth: 2.5,
              borderRadius: 5,
              borderSkipped: false,
              maxBarThickness: 22
            };
          };

          // Sombreado suave de sabados y domingos
          var weekendBands = {
            id: 'weekendBands',
            beforeDatasetsDraw: function (chart) {
              if (!WEEKENDS.length || chart.data.labels.length < 2) return;
              var xs = chart.scales.x;
              var area = chart.chartArea;
              var half = Math.abs(xs.getPixelForValue(1) - xs.getPixelForValue(0)) / 2;
              var c = chart.ctx;
              c.save();
              c.fillStyle = 'rgba(255,255,255,0.035)';
              WEEKENDS.forEach(function (i) {
                var x = xs.getPixelForValue(i);
                var left = Math.max(area.left, x - half);
                var right = Math.min(area.right, x + half);
                if (right > left) c.fillRect(left, area.top, right - left, area.bottom - area.top);
              });
              c.restore();
            }
          };

          // Linea vertical punteada siguiendo el cursor
          var crosshair = {
            id: 'crosshair',
            afterDatasetsDraw: function (chart) {
              var active = chart.tooltip && chart.tooltip.getActiveElements();
              if (!active || !active.length) return;
              var x = active[0].element.x;
              var area = chart.chartArea;
              var c = chart.ctx;
              c.save();
              c.beginPath();
              c.setLineDash([4, 4]);
              c.lineWidth = 1;
              c.strokeStyle = 'rgba(255,255,255,0.3)';
              c.moveTo(x, area.top);
              c.lineTo(x, area.bottom);
              c.stroke();
              c.restore();
            }
          };

          // Linea punteada con el promedio diario de vistas
          var avgLine = {
            id: 'avgLine',
            afterDatasetsDraw: function (chart) {
              if (!AVG_VIEWS || !chart.isDatasetVisible(0)) return;
              var y = chart.scales.y.getPixelForValue(AVG_VIEWS);
              var area = chart.chartArea;
              if (y <= area.top + 14 || y >= area.bottom) return;
              var c = chart.ctx;
              c.save();
              c.beginPath();
              c.setLineDash([6, 5]);
              c.lineWidth = 1;
              c.strokeStyle = 'rgba(255,255,255,0.26)';
              c.moveTo(area.left, y);
              c.lineTo(area.right, y);
              c.stroke();
              c.setLineDash([]);
              c.font = '600 10px Host Grotesk, sans-serif';
              var tw = c.measureText(AVG_LABEL).width;
              c.fillStyle = 'rgba(11,13,17,0.85)';
              c.fillRect(area.right - tw - 14, y - 16, tw + 12, 15);
              c.fillStyle = 'rgba(255,255,255,0.55)';
              c.fillText(AVG_LABEL, area.right - tw - 8, y - 5);
              c.restore();
            }
          };

          mainChart = new Chart(ctx, {
            type: 'line',
            plugins: [weekendBands, crosshair, avgLine],
            data: {
              labels: <?= json_encode($chartLabels) ?>,
              datasets: [
                makeDataset('Vistas',     <?= json_encode($chartPageviews) ?>, COLORS.views,    0.34),
                makeDataset('Visitantes', <?= json_encode($chartVisitors) ?>,  COLORS.visitors, 0.22),
                makeDataset('Clicks',     <?= json_encode($chartClicks) ?>,    COLORS.clicks,   0.16),
                makeDataset('WhatsApp',   <?= json_encode($chartWhatsapp) ?>,  COLORS.whatsapp, 0.18)
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              animation: { duration: 650, easing: 'easeOutQuart' },
              interaction: { intersect: false, mode: 'index' },
              layout: { padding: { top: 6 } },
              plugins: {
                legend: { display: false },
                tooltip: Object.assign({}, tooltipStyle, {
                  callbacks: {
                    title: function (items) {
                      var i = items.length ? items[0].dataIndex : -1;
                      var base = FULL_LABELS[i] || (items.length ? items[0].label : '');
                      return WEEKENDS.indexOf(i) !== -1 ? base + ' · finde' : base;
                    },
                    label: function (item) { return '  ' + item.dataset.label + ': ' + item.formattedValue; }
                  }
                })
              },
              scales: {
                x: {
                  grid: { display: false },
                  border: { display: false },
                  ticks: {
                    font: { size: 11 },
                    maxTicksLimit: 10,
                    maxRotation: 0,
                    padding: 8,
                    color: function (tick) {
                      return WEEKENDS.indexOf(tick.index) !== -1 ? 'rgba(255,255,255,0.62)' : 'rgba(255,255,255,0.35)';
                    }
                  }
                },
                y: {
                  grid: { color: 'rgba(255,255,255,0.055)', tickLength: 0 },
                  border: { display: false },
                  ticks: {
                    font: { size: 11 },
                    padding: 10,
                    maxTicksLimit: 6,
                    precision: 0,
                    callback: function (v) { return v >= 1000 ? (v / 1000).toFixed(v % 1000 === 0 ? 0 : 1) + 'k' : v; }
                  },
                  beginAtZero: true
                }
              }
            }
          });

          // Toggle de series desde la leyenda custom
          var legend = document.getElementById('chartLegend');
          if (legend) {
            legend.addEventListener('click', function (e) {
              var btn = e.target.closest('button[data-ds]');
              if (!btn) return;
              var i = parseInt(btn.dataset.ds, 10);
              var visible = mainChart.isDatasetVisible(i);
              mainChart.setDatasetVisibility(i, !visible);
              btn.classList.toggle('is-on', visible === false ? true : false);
              btn.classList.toggle('is-off', visible);
              mainChart.update();
            });
          }

          // Toggle linea / barras
          var btnLine = document.getElementById('chartTypeLine');
          var btnBar = document.getElementById('chartTypeBar');

          var setType = function (type) {
            mainChart.config.type = type;
            mainChart.data.datasets.forEach(function (ds) { ds.fill = type === 'line'; });
            mainChart.update();
            btnLine.classList.toggle('is-active', type === 'line');
            btnBar.classList.toggle('is-active', type === 'bar');
          };

          if (btnLine && btnBar) {
            btnLine.addEventListener('click', function () { setType('line'); });
            btnBar.addEventListener('click', function () { setType('bar'); });
          }
        }

        // ── Horarios ───────────────────────────────────
        var hourlyCanvas = document.getElementById('hourlyChart');
        if (hourlyCanvas) {
          new Chart(hourlyCanvas.getContext('2d'), {
            type: 'bar',
            data: {
              labels: Array.from({ length: 24 }, function (_, i) { return (i < 10 ? '0' : '') + i + 'h'; }),
              datasets: [{
                data: <?= json_encode(array_values($hourly)) ?>,
                backgroundColor: 'rgba(255, 90, 96, 0.55)',
                hoverBackgroundColor: '#ff5a60',
                borderRadius: 3,
                maxBarThickness: 18
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: Object.assign({}, tooltipStyle, {
                  callbacks: {
                    title: function (items) { return items[0].label; },
                    label: function (item) { return '  ' + item.formattedValue + ' vistas'; }
                  }
                })
              },
              scales: {
                x: {
                  grid: { display: false },
                  border: { display: false },
                  ticks: { font: { size: 10 }, maxTicksLimit: 8, maxRotation: 0 }
                },
                y: {
                  grid: { color: 'rgba(255,255,255,0.05)', tickLength: 0 },
                  border: { display: false },
                  ticks: { font: { size: 10 }, maxTicksLimit: 5, precision: 0 },
                  beginAtZero: true
                }
              }
            }
          });
        }

        // ── Dispositivos ───────────────────────────────
        var devicesCanvas = document.getElementById('devicesChart');
        if (devicesCanvas) {
          var deviceData = <?= json_encode(array_values($devices)) ?>;
          var deviceLabels = <?= json_encode(array_keys($devices)) ?>;
          var deviceColorMap = { 'Celular': '#54c6ff', 'Computadora': '#ff5a60', 'Tablet': '#f6c85f' };

          new Chart(devicesCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
              labels: deviceLabels,
              datasets: [{
                data: deviceData,
                backgroundColor: deviceLabels.map(function (l) { return deviceColorMap[l] || '#888'; }),
                borderColor: '#0b0d11',
                borderWidth: 3,
                hoverOffset: 6
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '68%',
              plugins: {
                legend: { display: false },
                tooltip: Object.assign({}, tooltipStyle, {
                  callbacks: {
                    label: function (item) { return '  ' + item.label + ': ' + item.formattedValue + ' vistas'; }
                  }
                })
              }
            }
          });
        }
      })();

      // ── Filtros de la tabla de eventos ─────────────
      (function () {
        var table = document.getElementById('eventsTable');
        if (!table) return;

        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr[data-type]'));
        var searchInput = document.getElementById('eventSearch');
        var filterButtons = document.querySelectorAll('.metrics-events__controls [data-filter]');
        var moreBtn = document.getElementById('eventsMore');
        var countEl = document.getElementById('eventsCount');

        var PAGE = 15;
        var shown = PAGE;
        var activeFilter = 'all';

        function apply() {
          var query = (searchInput.value || '').toLowerCase().trim();
          var matched = rows.filter(function (row) {
            var okType = activeFilter === 'all' || row.dataset.type === activeFilter;
            var okSearch = !query || row.dataset.search.indexOf(query) !== -1;
            return okType && okSearch;
          });

          rows.forEach(function (row) { row.style.display = 'none'; });
          matched.slice(0, shown).forEach(function (row) { row.style.display = ''; });

          if (countEl) {
            countEl.textContent = matched.length === 0
              ? 'Sin resultados para este filtro.'
              : 'Mostrando ' + Math.min(shown, matched.length) + ' de ' + matched.length + ' eventos';
          }
          if (moreBtn) {
            moreBtn.style.display = matched.length > shown ? '' : 'none';
          }
        }

        filterButtons.forEach(function (btn) {
          btn.addEventListener('click', function () {
            filterButtons.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            activeFilter = btn.dataset.filter;
            shown = PAGE;
            apply();
          });
        });

        if (searchInput) {
          searchInput.addEventListener('input', function () {
            shown = PAGE;
            apply();
          });
        }

        if (moreBtn) {
          moreBtn.addEventListener('click', function () {
            shown += PAGE;
            apply();
          });
        }

        apply();
      })();
      </script>
    <?php endif; ?>
  </main>
</body>
</html>
