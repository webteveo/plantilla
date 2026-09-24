<?php

require __DIR__ . '/../src/libs/Metrics.php';

use benjamin\plantillaweb\libs\Metrics;

$cases = [
    [['referrer' => 'https://chatgpt.com/'], 'ChatGPT'],
    [['referrer' => 'https://chat.openai.com/'], 'ChatGPT'],
    [['referrer' => 'https://t.co/abc'], 'X / Twitter'],
    [['referrer' => 'https://mobile.twitter.com/'], 'X / Twitter'],
    [['referrer' => 'https://x.com/'], 'X / Twitter'],
    [['referrer' => 'https://l.facebook.com/'], 'Facebook'],
    [['referrer' => 'https://www.google.com.uy/'], 'Google'],
    [['referrer' => 'https://notgoogle.com/'], 'notgoogle.com'],
    [['referrer' => 'https://google.com.example.org/'], 'google.com.example.org'],
    [['referrer' => 'https://example.org/?utm_source=twitter'], 'example.org'],
    [['referrer' => 'https://t.co/', 'utm_source' => 'chatgpt'], 'chatgpt'],
    [['utm_source' => 'newsletter'], 'newsletter'],
    [['referrer' => 'https://chatgpt.com/', 'utm_source' => '  '], 'ChatGPT'],
    [[], 'Directo / sin referrer'],
];

foreach ($cases as [$event, $expected]) {
    $actual = Metrics::eventSource($event);
    if ($actual !== $expected) {
        throw new RuntimeException("Expected $expected, got $actual");
    }
}

$isAuthenticated = true;
$ruta = '/mudanzasmontevideo/public';
$summary = [
    'insights' => ['best_hour' => null, 'best_weekday' => null],
    'recent' => [['type' => 'pageview', 'utm_source' => 'campaign"<tag>', 'referrer' => 'https://chatgpt.com/']],
];
ob_start();
require __DIR__ . '/../src/vista/metricas/index.php';
$html = ob_get_clean();
foreach (['>campaign&quot;&lt;tag&gt;</td>', 'title="utm_source: campaign&quot;&lt;tag&gt; | Referencia: https://chatgpt.com/"'] as $expected) {
    if (!str_contains($html, $expected)) {
        throw new RuntimeException('Source or tooltip rendered incorrectly');
    }
}

echo "OK: " . count($cases) . " source cases and escaped table/tooltip rendering.\n";
