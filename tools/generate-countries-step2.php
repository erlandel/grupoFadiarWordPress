<?php
/**
 * Script generador paso 2 (final): toma countries.json (paises+nombre+dial, flag_svg='')
 * y le inyecta las banderas SVG inline leidas desde components/contact-form/data/flags/{code}.svg.
 * Sobrescribe countries.json con el contenido final + banderas inline.
 *
 * Ejecutar una sola vez desde la raiz del tema:
 *   php -c <ruta-al-php.ini> tools/generate-countries-step2.php
 */

declare(strict_types=1);

$jsonPath = __DIR__ . '/../components/contact-form/data/countries.json';
$flagDir = __DIR__ . '/../components/contact-form/data/flags';

$list = json_decode(file_get_contents($jsonPath), true);
if (!is_array($list)) {
    fwrite(STDERR, "ERROR: countries.json invalido" . PHP_EOL);
    exit(1);
}

fwrite(STDOUT, "Pais a procesar: " . count($list) . PHP_EOL);

$filled = 0;
$skipped = 0;
foreach ($list as &$c) {
    $code = strtolower($c['code']);
    $flagFile = $flagDir . '/' . $code . '.svg';
    if (is_file($flagFile)) {
        $svg = file_get_contents($flagFile);
        if ($svg !== false && strpos($svg, '<svg') !== false) {
            $c['flag_svg'] = $svg;
            $filled++;
        } else {
            $skipped++;
        }
    } else {
        $skipped++;
    }
}
unset($c);

$bytes = file_put_contents(
    $jsonPath,
    json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);
fwrite(STDOUT, "OK: banderas inlineadas en $filled paises" . PHP_EOL);
fwrite(STDOUT, "SKIPPED (sin bandera): $skipped" . PHP_EOL);
fwrite(STDOUT, "Tamano final: " . round($bytes / 1024, 1) . " KB" . PHP_EOL);
