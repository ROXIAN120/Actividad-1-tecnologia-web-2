<?php
// src/almacen.php — Guardar y leer los productos en un archivo JSON (antes de tener base de datos).
declare(strict_types=1);

const ARCHIVO_PRODUCTOS = __DIR__ . '/../datos/productos.json';

/** @return array<int, array<string, mixed>> */
function leer_productos(string $archivo = ARCHIVO_PRODUCTOS): array
{
    if (!is_file($archivo)) {
        return [];
    }
    $productos = json_decode((string) file_get_contents($archivo), true, 512, JSON_THROW_ON_ERROR);
    return is_array($productos) ? $productos : [];
}

/** Guarda con bloqueo exclusivo (LOCK_EX): dos personas guardando a la vez no mezclan el archivo.
 *  JSON_PRESERVE_ZERO_FRACTION: 45.0 se guarda como 45.0. Sin ella queda 45 y al leerlo vuelve como int: PHP lo
 *  acepta donde se espera float, pero el tipo cambió (y una API o JavaScript verían un entero). */
function guardar_productos(array $productos, string $archivo = ARCHIVO_PRODUCTOS): void
{
    $json = json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
    file_put_contents($archivo, $json . PHP_EOL, LOCK_EX);
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function bs(float $monto): string
{
    return 'Bs ' . number_format($monto, 2, ',', '.');
}
