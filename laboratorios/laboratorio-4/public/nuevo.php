<?php
// public/nuevo.php — Registrar un producto con foto: GET muestra el formulario; POST valida, guarda y redirige (PRG).
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';
require __DIR__ . '/../src/validacion.php';
require __DIR__ . '/../src/fotos.php';

$errores = [];
$datos = ['codigo' => '', 'nombre' => '', 'categoria' => '', 'precio' => '', 'stock' => '', 'socio' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = leer_productos();
    $resultado = validar_producto($_POST, $productos);
    $errores = $resultado['errores'];
    $foto = guardar_foto($_FILES);
    if ($foto['error'] !== null) {
        $errores['foto'] = $foto['error'];
    }
    if ($errores === []) {
        $productos[] = $resultado['datos'] + ['foto' => $foto['nombre']];
        guardar_productos($productos);
        header('Location: /?creado=' . urlencode($resultado['datos']['codigo']), true, 303);
        exit;
    }
    borrar_foto($foto['nombre']);                   // si el resto falló, no dejar una foto huérfana
    http_response_code(422);
    $datos = array_map('strval', $_POST + $datos);
}

$titulo = 'Registrar producto';
$accion = '/nuevo.php';
$editando = false;
require __DIR__ . '/../plantillas/cabecera.php';
echo '<h2>Registrar producto</h2>';
require __DIR__ . '/../plantillas/formulario.php';
require __DIR__ . '/../plantillas/pie.php';
