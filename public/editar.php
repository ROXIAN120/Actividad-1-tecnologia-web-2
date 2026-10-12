<?php
// public/editar.php?codigo=MIE-500 — Editar un producto; si llega una foto nueva, reemplaza a la anterior.
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';
require __DIR__ . '/../src/validacion.php';
require __DIR__ . '/../src/fotos.php';

$codigo = (string) ($_GET['codigo'] ?? '');
$productos = leer_productos();
$indice = array_search($codigo, array_column($productos, 'codigo'), true);
if ($indice === false) {
    http_response_code(404);
    exit('Producto no encontrado');
}
$actual = $productos[$indice];
$errores = [];
$datos = array_map('strval', $actual);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otros = $productos;
    unset($otros[$indice]);                         // al validar, el código propio no cuenta como repetido
    $resultado = validar_producto(['codigo' => $codigo] + $_POST, array_values($otros));  // el código no se cambia
    $errores = $resultado['errores'];
    $foto = guardar_foto($_FILES);
    if ($foto['error'] !== null) {
        $errores['foto'] = $foto['error'];
    }
    if ($errores === []) {
        $nuevaFoto = $foto['nombre'] ?? ($actual['foto'] ?? null);
        if ($foto['nombre'] !== null) {
            borrar_foto($actual['foto'] ?? null);
        }
        $productos[$indice] = $resultado['datos'] + ['foto' => $nuevaFoto];
        guardar_productos($productos);
        header('Location: /?actualizado=' . urlencode($codigo), true, 303);
        exit;
    }
    borrar_foto($foto['nombre']);
    http_response_code(422);
    $datos = array_map('strval', ['codigo' => $codigo] + $_POST + $datos);
}

$titulo = 'Editar ' . $codigo;
$accion = '/editar.php?codigo=' . urlencode($codigo);
$editando = true;
require __DIR__ . '/../plantillas/cabecera.php';
echo '<h2>Editar ' . e($codigo) . '</h2>';
if (!empty($actual['foto'])) {
    echo '<p><img src="/foto.php?f=' . e($actual['foto']) . '" alt="Foto actual de ' . e($actual['nombre']) . '" width="160"></p>';
}
require __DIR__ . '/../plantillas/formulario.php';
require __DIR__ . '/../plantillas/pie.php';
