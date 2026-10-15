<?php
declare(strict_types=1);

require __DIR__ . '/src/inventario.php';
$datos = require __DIR__ . '/datos.php';

$pruebas = [
    'Descuento de socio del 15 % con argumento nombrado' =>
        precio_con_descuento(45.0, 1, esSocio: true) === 38.25,
    'Stock agrupado por socio' => stock_por_socio($datos) === [
        'Elena Masabí' => 34,
        'Mario Surubí' => 4,
        'Rosa Chuvé' => 50,
    ],
    'Cálculo equivalente en centavos enteros' =>
        precio_con_descuento_centavos(4500, 12) === 48600
        && precio_con_descuento(45.0, 12) === 486.0,
];

$correctas = 0;
foreach ($pruebas as $descripcion => $resultado) {
    if ($resultado) {
        $correctas++;
    }
    echo ($resultado ? '✅ ' : '❌ '), $descripcion, PHP_EOL;
}

echo "Resultado de retos: $correctas de ", count($pruebas), PHP_EOL;
exit($correctas === count($pruebas) ? 0 : 1);
