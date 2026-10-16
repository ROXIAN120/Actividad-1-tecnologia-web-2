<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($titulo) ?> | Catálogo de apicultores</title><link rel="stylesheet" href="/estilo.css"></head>
<body><header><div class="cabecera"><p class="marca">Apicultores Flor de la Chiquitanía</p><p>Actividad 02 · Catálogo MySQL</p><nav aria-label="Principal"><a href="/productos">Productos</a><a href="/socios">Socios</a><a href="/productos/nuevo">Registrar producto</a><a href="/socios/nuevo">Registrar socio</a></nav></div></header>
<main><h1><?= e($titulo) ?></h1><?php if ($mensaje): ?><p class="exito" role="status"><?= e($mensaje) ?></p><?php endif ?>
<?php require __DIR__.'/'.$vista.'.php'; ?></main><footer>Grupo HTML · Rodrigo Ledezma Sanchez · PHP y PDO · Entorno local de demostración</footer></body></html>
