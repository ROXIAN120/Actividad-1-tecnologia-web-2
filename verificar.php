<?php
// verificar.php — Comprueba el Laboratorio 2 (pruebas de funciones + reporte).  Uso:  php verificar.php
declare(strict_types=1);
require is_file(__DIR__ . '/Verificador.php') ? __DIR__ . '/Verificador.php' : __DIR__ . '/../../comun/Verificador.php';
require __DIR__ . '/src/inventario.php';
$datos = require __DIR__ . '/datos.php';
$v = new Verificador();

$v->revisar('precio_con_descuento: 5 u sin descuento, 6 u 5 %, 12 u 10 %', fn() =>
    precio_con_descuento(45.0, 5) === 225.0 && precio_con_descuento(45.0, 6) === 256.5 && precio_con_descuento(45.0, 12) === 486.0);
$v->revisar('clasificar_stock: 0 Agotado · 5 Bajo · 20 Normal · 21 Alto', fn() =>
    [clasificar_stock(0), clasificar_stock(5), clasificar_stock(20), clasificar_stock(21)] === ['Agotado', 'Bajo', 'Normal', 'Alto']);
$v->revisar('filtrar_por_categoria sin distinguir mayúsculas y con índices 0..n', fn() =>
    count(filtrar_por_categoria($datos, 'miel')) === 3 && array_keys(filtrar_por_categoria($datos, 'DERIVADOS')) === [0, 1]);
$v->revisar('ordenar_por_precio ascendente y descendente (sin tocar el original)', fn() =>
    ordenar_por_precio($datos)[0]['codigo'] === 'PRO-30' && ordenar_por_precio($datos, true)[0]['codigo'] === 'CER-1K'
    && $datos[0]['codigo'] === 'MIE-500');
$v->revisar('valor_inventario = Bs 5.030,00', fn() => abs(valor_inventario($datos) - 5030.0) < 0.001);
$v->revisar('resumen_por_categoria ordenado con productos y unidades', fn() => resumen_por_categoria($datos) === [
    'Cera' => ['productos' => 1, 'unidades' => 4],
    'Derivados' => ['productos' => 2, 'unidades' => 34],
    'Miel' => ['productos' => 3, 'unidades' => 50],
]);
$v->revisar('slug con tildes, ñ y símbolos', fn() =>
    slug('Miel de cusí 350 g') === 'miel-de-cusi-350-g' && slug('  Polen & Propóleo (Año 2026) ') === 'polen-propoleo-ano-2026');
$v->revisar('codigo_valido acepta MIE-500 y CER-1K; rechaza mie-500, MI-500 y MIE-12345', fn() =>
    codigo_valido('MIE-500') && codigo_valido('CER-1K') && !codigo_valido('mie-500') && !codigo_valido('MI-500')
    && !codigo_valido('MIE-12345'));
$v->revisar('iniciales con tildes (Élida Chuvé → ÉC)', fn() => iniciales('Élida Chuvé') === 'ÉC' && iniciales('  Rosa   María Chuvé ') === 'RMC');

$v->servidor(__DIR__ . '/public');
$r = $v->pedir('/reporte.php');
$v->revisar('Reporte: valor total y resumen por categoría', fn() =>
    str_contains($r['cuerpo'], 'Valor total: Bs 5.030,00') && str_contains($r['cuerpo'], '>Derivados</a>'));
$v->revisar('Reporte filtrado ?categoria=Cera muestra solo la cera', fn() =>
    substr_count($v->pedir('/reporte.php?categoria=Cera')['cuerpo'], '/producto/') === 1);
$v->revisar('El filtro escapa lo que recibe por la URL', fn() =>
    str_contains($v->pedir('/reporte.php?categoria=%3Cscript%3E')['cuerpo'], 'de &lt;script&gt;'));
exit($v->fin());
