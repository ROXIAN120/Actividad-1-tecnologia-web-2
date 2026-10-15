<?php
// public/eliminar.php — Elimina un producto (solo por POST) y su foto; luego redirige (PRG).
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';
require __DIR__ . '/../src/fotos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Método no permitido');
}
$codigo = (string) ($_POST['codigo'] ?? '');
$productos = leer_productos();
$restantes = [];
$borrado = false;
foreach ($productos as $p) {
    if ($p['codigo'] === $codigo) {
        borrar_foto($p['foto'] ?? null);
        $borrado = true;
        continue;
    }
    $restantes[] = $p;
}
if ($borrado) {
    guardar_productos($restantes);
}
header('Location: /?eliminado=' . urlencode($borrado ? $codigo : ''), true, 303);
