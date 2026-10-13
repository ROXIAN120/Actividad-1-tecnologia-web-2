<?php
// plantillas/cabecera.php — Parte común de todas las páginas (se incluye con require).
// Espera la variable $titulo definida por la página que la incluye.
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?> · Flor de la Chiquitanía</title>
  <link rel="stylesheet" href="/css/estilo.css">
</head>
<body>
<header>
  <h1>Apicultores Flor de la Chiquitanía</h1>
  <nav><a href="/">Catálogo</a> · <a href="/nuevo.php">Registrar producto</a></nav>
</header>
<main>
