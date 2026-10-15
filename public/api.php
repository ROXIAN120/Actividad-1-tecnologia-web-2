<?php
// public/api.php — El catálogo en JSON (anticipo de la API REST de la clase 13): json_encode usa jsonSerialize().
declare(strict_types=1);
// TODO 6: reemplaza estos require manuales por UNA línea: el autoload de Composer (como en index.php).
//         ¿Qué pasa con estos require cuando el proyecto tenga 40 clases?
require __DIR__ . '/../vendor/autoload.php';

use Apicultores\Catalogo;

$catalogo = Catalogo::desdeArchivo(__DIR__ . '/../datos/catalogo.json');
header('Content-Type: application/json; charset=utf-8');
echo json_encode($catalogo->todos(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
