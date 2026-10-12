<?php
// src/validacion.php — Validación del formulario de productos: NUNCA se confía en lo que envía el navegador.
declare(strict_types=1);

const CATEGORIAS = ['Miel', 'Derivados', 'Cera'];

/**
 * Valida y normaliza los datos de un producto.
 * @param array<string, mixed> $entrada    normalmente $_POST (todo llega como texto)
 * @param array<int, array>    $existentes productos ya guardados (para evitar códigos repetidos)
 * @return array{datos: array<string, mixed>, errores: array<string, string>}
 */
function validar_producto(array $entrada, array $existentes): array
{
    $errores = [];
    $codigo = strtoupper(trim((string) ($entrada['codigo'] ?? '')));
    $nombre = trim((string) ($entrada['nombre'] ?? ''));
    $categoria = (string) ($entrada['categoria'] ?? '');
    $socio = trim((string) ($entrada['socio'] ?? ''));
    // En Bolivia se escribe 45,50: se acepta la coma decimal
    $precioTexto = str_replace(',', '.', trim((string) ($entrada['precio'] ?? '')));
    $stockTexto = trim((string) ($entrada['stock'] ?? ''));

    if (!preg_match('/^[A-Z]{3}-[A-Z0-9]{2,4}$/', $codigo)) {
        $errores['codigo'] = 'El código debe tener el formato AAA-999 (ej. MIE-500).';
    } elseif (in_array($codigo, array_column($existentes, 'codigo'), true)) {
        $errores['codigo'] = "Ya existe un producto con el código $codigo.";
    }
    $largo = mb_strlen($nombre);
    if ($largo < 3 || $largo > 80) {
        $errores['nombre'] = 'El nombre debe tener entre 3 y 80 caracteres.';
    }
    if (!in_array($categoria, CATEGORIAS, true)) {
        $errores['categoria'] = 'Elige una categoría de la lista.';
    }
    $precio = filter_var($precioTexto, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0.01, 'max_range' => 10000]]);
    if ($precio === false) {
        $errores['precio'] = 'El precio debe ser un número entre 0,01 y 10.000.';
    }
    $stock = filter_var($stockTexto, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100000]]);
    if ($stock === false) {
        $errores['stock'] = 'El stock debe ser un número entero mayor o igual a 0.';
    }
    if ($socio === '') {
        $errores['socio'] = 'Indica el socio que produjo el lote.';
    }
    // Texto que no es UTF-8 válido (p. ej. enviado con otra codificación) rompería json_encode más adelante
    foreach (['codigo', 'nombre', 'categoria', 'socio'] as $campo) {
        if (!mb_check_encoding((string) ($entrada[$campo] ?? ''), 'UTF-8')) {
            $errores[$campo] = 'El texto tiene caracteres con una codificación inválida (se espera UTF-8).';
        }
    }

    $datos = [
        'codigo' => $codigo, 'nombre' => $nombre, 'categoria' => $categoria,
        'precio' => $precio === false ? 0.0 : round((float) $precio, 2),
        'stock' => $stock === false ? 0 : (int) $stock, 'socio' => $socio,
    ];
    return ['datos' => $datos, 'errores' => $errores];
}
