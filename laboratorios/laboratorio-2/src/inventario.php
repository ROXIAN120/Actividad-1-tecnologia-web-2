<?php
// src/inventario.php — Funciones de la asociación: precios, stock, filtros, reportes y textos.
declare(strict_types=1);

/** Descuento por volumen: docena o más 10 %, media docena o más 5 %, si no, precio normal. */
function precio_con_descuento(float $precio, int $cantidad, bool $esSocio = false): float
{
    $porcentajePago = match (true) {
        $esSocio => 85,
        $cantidad >= 12 => 90,
        $cantidad >= 6 => 95,
        default => 100,
    };

    $precioCentavos = (int) round($precio * 100);
    $totalCentavos = intdiv($precioCentavos * $cantidad * $porcentajePago + 50, 100);

    return $totalCentavos / 100;
}

/** Nivel de stock para alertas del acopio. */
function clasificar_stock(int $stock): string
{
    return match (true) {
        $stock === 0 => 'Agotado',
        $stock <= 5 => 'Bajo',
        $stock <= 20 => 'Normal',
        default => 'Alto',
    };
}

/** Productos de una categoría (sin distinguir mayúsculas), con índices 0, 1, 2… */
function filtrar_por_categoria(array $productos, string $categoria): array
{
    $categoriaNormalizada = mb_strtolower(trim($categoria), 'UTF-8');

    return array_values(array_filter(
        $productos,
        fn(array $p): bool => mb_strtolower($p['categoria'], 'UTF-8') === $categoriaNormalizada
    ));
}

/** Copia ordenada por precio (ascendente o descendente) con el operador nave espacial <=>. */
function ordenar_por_precio(array $productos, bool $descendente = false): array
{
    usort($productos, fn(array $a, array $b): int => $descendente ? $b['precio'] <=> $a['precio'] : $a['precio'] <=> $b['precio']);
    return $productos;
}

/** Valor del inventario: suma de precio × stock. */
function valor_inventario(array $productos): float
{
    return array_sum(array_map(
        fn(array $p): float => (float) $p['precio'] * (int) $p['stock'],
        $productos
    ));
}

/** Resumen por categoría: ['Miel' => ['productos' => 3, 'unidades' => 50], …] ordenado por nombre. */
function resumen_por_categoria(array $productos): array
{
    $resumen = [];
    foreach ($productos as $p) {
        $c = $p['categoria'];
        $resumen[$c] ??= ['productos' => 0, 'unidades' => 0];
        $resumen[$c]['productos']++;
        $resumen[$c]['unidades'] += $p['stock'];
    }
    ksort($resumen);
    return $resumen;
}

/** Texto apto para URL: "Miel de cusí 350 g" → "miel-de-cusi-350-g" (tildes y ñ incluidas). */
function slug(string $texto): string
{
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ü' => 'u', 'ñ' => 'n',
    ]);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto) ?? '';

    return trim($texto, '-');
}

/** Código de producto válido: 3 letras mayúsculas, guion y 2 a 4 letras o dígitos (MIE-500, CER-1K). */
function codigo_valido(string $codigo): bool
{
    return preg_match('/^[A-Z]{3}-[A-Z0-9]{2,4}$/', $codigo) === 1;
}

/** Total en centavos para comparar el cálculo monetario sin números decimales. */
function precio_con_descuento_centavos(int $precioCentavos, int $cantidad, bool $esSocio = false): int
{
    $porcentajePago = match (true) {
        $esSocio => 85,
        $cantidad >= 12 => 90,
        $cantidad >= 6 => 95,
        default => 100,
    };

    return intdiv($precioCentavos * $cantidad * $porcentajePago + 50, 100);
}

/** Unidades disponibles agrupadas por socio y ordenadas por nombre. */
function stock_por_socio(array $productos): array
{
    $stock = [];
    foreach ($productos as $producto) {
        $socio = $producto['socio'];
        $stock[$socio] = ($stock[$socio] ?? 0) + (int) $producto['stock'];
    }
    ksort($stock);

    return $stock;
}

/** Iniciales de un nombre con tildes: "Élida Chuvé" → "ÉC". */
function iniciales(string $nombre): string
{
    $partes = preg_split('/\s+/', trim($nombre), -1, PREG_SPLIT_NO_EMPTY);
    return implode('', array_map(fn(string $p): string => mb_strtoupper(mb_substr($p, 0, 1)), $partes));
}
