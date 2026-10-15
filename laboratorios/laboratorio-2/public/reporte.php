<?php
// public/reporte.php — Reporte del inventario usando las funciones de src/inventario.php
declare(strict_types=1);
require __DIR__ . '/../src/inventario.php';
$productos = require __DIR__ . '/../datos.php';

function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); }
function bs(float $m): string { return 'Bs ' . number_format($m, 2, ',', '.'); }

$categoria = $_GET['categoria'] ?? '';
$lista = $categoria !== '' ? filtrar_por_categoria($productos, $categoria) : $productos;
$lista = ordenar_por_precio($lista, ($_GET['orden'] ?? '') === 'desc');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reporte de inventario · Flor de la Chiquitanía</title>
  <link rel="stylesheet" href="/css/estilo.css">
</head>
<body>
<header>
  <h1>Reporte de inventario</h1>
  <p>Valor total: <?= bs(valor_inventario($productos)) ?></p>
</header>
<main>
  <table>
    <caption>Por categoría</caption>
    <thead><tr><th scope="col">Categoría</th><th scope="col">Productos</th><th scope="col">Unidades</th></tr></thead>
    <tbody>
    <?php foreach (resumen_por_categoria($productos) as $cat => $r): ?>
      <tr>
        <td><a href="?categoria=<?= urlencode($cat) ?>"><?= e($cat) ?></a></td>
        <td class="num"><?= $r['productos'] ?></td>
        <td class="num"><?= $r['unidades'] ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <table>
    <caption>Stock por socio</caption>
    <thead><tr><th scope="col">Socio</th><th scope="col">Unidades</th></tr></thead>
    <tbody>
    <?php foreach (stock_por_socio($productos) as $socio => $unidades): ?>
      <tr>
        <td><?= e($socio) ?></td>
        <td class="num"><?= $unidades ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <table>
    <caption>Productos<?= $categoria !== '' ? ' de ' . e($categoria) : '' ?> (por precio)</caption>
    <thead>
      <tr><th scope="col">Producto</th><th scope="col">Enlace</th><th scope="col">Socio</th><th scope="col">Precio</th>
          <th scope="col">Stock</th><th scope="col">12 unidades</th><th scope="col">Precio socio</th></tr>
    </thead>
    <tbody>
    <?php foreach ($lista as $p): ?>
      <tr>
        <td><?= e($p['nombre']) ?></td>
        <td>/producto/<?= e(slug($p['nombre'])) ?></td>
        <td><?= e(iniciales($p['socio'])) ?></td>
        <td class="num"><?= bs($p['precio']) ?></td>
        <td><?= clasificar_stock($p['stock']) ?></td>
        <td class="num"><?= bs(precio_con_descuento($p['precio'], 12)) ?></td>
        <td class="num"><?= bs(precio_con_descuento($p['precio'], 1, esSocio: true)) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
