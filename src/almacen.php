<?php
// src/almacen.php — Guardar y leer los productos en un archivo JSON (antes de tener base de datos).
declare(strict_types=1);

require_once __DIR__ . '/Catalogo.php';

const ARCHIVO_PRODUCTOS = __DIR__ . '/../datos/productos.json';

/** @return array<int, array<string, mixed>> */
function leer_productos(string $archivo = ARCHIVO_PRODUCTOS): array
{
    return array_map(
        fn(Producto $producto): array => $producto->aArray(),
        (new Catalogo($archivo))->todos()
    );
}

/** Guarda con bloqueo exclusivo (LOCK_EX): dos personas guardando a la vez no mezclan el archivo.
 *  JSON_PRESERVE_ZERO_FRACTION: 45.0 se guarda como 45.0. Sin ella queda 45 y al leerlo vuelve como int: PHP lo
 *  acepta donde se espera float, pero el tipo cambió (y una API o JavaScript verían un entero). */
function guardar_productos(array $productos, string $archivo = ARCHIVO_PRODUCTOS): void
{
    $objetos = array_map(
        fn(array $producto): Producto => Producto::desdeArray($producto),
        array_values($productos)
    );
    (new Catalogo($archivo))->guardar($objetos);
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function bs(float $monto): string
{
    return 'Bs ' . number_format($monto, 2, ',', '.');
}
