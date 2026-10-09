<?php
declare(strict_types=1);

require __DIR__ . '/src/inventario.php';

echo "PARTE A — Trampas del lenguaje", PHP_EOL;
var_dump(0.1 + 0.2 == 0.3);
var_dump(round(0.1 + 0.2, 2) == 0.3);
var_dump('1' == '01', '1' === '01', 0 == 'a', null ?? 'sin dato');
var_dump(strlen('miel ñ'), mb_strlen('miel ñ'));
var_dump(strtoupper('élida'), mb_strtoupper('élida'));
echo intdiv(17, 5), ' ', 17 % 5, ' ', 2 ** 10, PHP_EOL;

echo PHP_EOL, "PARTE B — switch frente a match", PHP_EOL;
$stock = '5';
switch ($stock) {
    case 5:
        echo "switch: coincide", PHP_EOL;
        break;
    default:
        echo "switch: no", PHP_EOL;
}
echo match ($stock) {
    5 => 'match: coincide',
    default => 'match: no coincide',
}, PHP_EOL;

try {
    echo match (99) {
        1 => 'uno',
    };
} catch (UnhandledMatchError $error) {
    echo 'Último match: ', $error::class, PHP_EOL;
}

echo PHP_EOL, "PARTE E — Tipos estrictos", PHP_EOL;
try {
    clasificar_stock('5');
} catch (TypeError $error) {
    echo $error::class, ': ', $error->getMessage(), PHP_EOL;
}
