<?php
// public/index.php — Catálogo generado por el servidor a partir de datos.
declare(strict_types=1);
date_default_timezone_set('America/La_Paz');

$productos = require __DIR__ . '/../datos.php';

/** Escapa texto para HTML y evita que los datos se interpreten como código. */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/** Presenta un monto con el formato monetario usado en Bolivia. */
function bs(float $monto): string
{
    return 'Bs ' . number_format($monto, 2, ',', '.');
}

$disponibles = 0;
foreach ($productos as $producto) {
    if ($producto['stock'] > 0) {
        $disponibles++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo · Apicultores Flor de la Chiquitanía</title>
  <link rel="stylesheet" href="/css/estilo.css">
</head>
<body>
  <header>
    <h1>Apicultores Flor de la Chiquitanía</h1>
    <p><?= count($productos) ?> productos · <?= $disponibles ?> disponibles</p>
  </header>
  <main>
    <table>
      <caption>Catálogo de la asociación</caption>
      <thead>
        <tr>
          <th scope="col">Código</th>
          <th scope="col">Producto</th>
          <th scope="col">Categoría</th>
          <th scope="col">Socio</th>
          <th scope="col">Precio</th>
          <th scope="col">Stock</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($productos as $p): ?>
        <tr<?= $p['stock'] === 0 ? ' class="agotado"' : '' ?>>
          <td><?= e($p['codigo']) ?></td>
          <td><?= e($p['nombre']) ?></td>
          <td><?= e($p['categoria']) ?></td>
          <td><?= e($p['socio']) ?></td>
          <td class="num"><?= bs((float) $p['precio']) ?></td>
          <td class="num">
            <?php if ($p['stock'] === 0): ?>
              <span class="insignia">Agotado</span>
            <?php else: ?>
              <?= e((string) $p['stock']) ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </main>
  <footer>
    <p>Generado por el servidor el <?= date('d/m/Y') ?> a las <?= date('H:i') ?> con PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?></p>
  </footer>
</body>
</html>
