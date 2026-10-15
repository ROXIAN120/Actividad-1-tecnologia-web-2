<?php
// public/index.php — Catálogo leído del archivo JSON, con búsqueda por GET y mensaje tras registrar.
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';

$productos = leer_productos();
$buscar = trim((string) ($_GET['q'] ?? ''));                  // GET: parámetros visibles en la URL (buscar, filtrar)
if ($buscar !== '') {
    $productos = array_values(array_filter(
        $productos,
        fn(array $p): bool => mb_stripos($p['nombre'], $buscar) !== false || mb_stripos($p['codigo'], $buscar) !== false
    ));
}
$creado = (string) ($_GET['creado'] ?? '');

$titulo = 'Catálogo';
require __DIR__ . '/../plantillas/cabecera.php';
?>
<?php if ($creado !== ''): ?>
  <p class="exito" role="status">Producto <?= e($creado) ?> registrado.</p>
<?php endif; ?>
<form method="get" action="/" role="search">
  <label for="q">Buscar</label>
  <input id="q" name="q" type="search" value="<?= e($buscar) ?>">
  <button type="submit">Buscar</button>
</form>
<table>
  <caption><?= count($productos) ?> productos<?= $buscar !== '' ? ' para «' . e($buscar) . '»' : '' ?></caption>
  <thead><tr><th scope="col">Código</th><th scope="col">Producto</th><th scope="col">Categoría</th><th scope="col">Precio</th><th scope="col">Stock</th></tr></thead>
  <tbody>
  <?php foreach ($productos as $p): ?>
    <tr>
      <td><?= e($p['codigo']) ?></td><td><?= e($p['nombre']) ?></td><td><?= e($p['categoria']) ?></td>
      <td class="num"><?= bs($p['precio']) ?></td><td class="num"><?= (int) $p['stock'] ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php require __DIR__ . '/../plantillas/pie.php'; ?>
