<?php
// public/index.php — Catálogo con fotos, búsqueda por GET, mensajes y acciones de editar y eliminar.
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';

$productos = leer_productos();
$buscar = trim((string) ($_GET['q'] ?? ''));
if ($buscar !== '') {
    $productos = array_values(array_filter(
        $productos,
        fn(array $p): bool => mb_stripos($p['nombre'], $buscar) !== false || mb_stripos($p['codigo'], $buscar) !== false
    ));
}
$mensajes = array_filter([
    'creado' => 'registrado', 'actualizado' => 'actualizado', 'eliminado' => 'eliminado',
], fn(string $clave): bool => ($_GET[$clave] ?? '') !== '', ARRAY_FILTER_USE_KEY);

$titulo = 'Catálogo';
require __DIR__ . '/../plantillas/cabecera.php';
?>
<?php foreach ($mensajes as $clave => $verbo): ?>
  <p class="exito" role="status">Producto <?= e((string) $_GET[$clave]) ?> <?= $verbo ?>.</p>
<?php endforeach; ?>
<form method="get" action="/" role="search">
  <label for="q">Buscar</label>
  <input id="q" name="q" type="search" value="<?= e($buscar) ?>">
  <button type="submit">Buscar</button>
</form>
<table>
  <caption><?= count($productos) ?> productos<?= $buscar !== '' ? ' para «' . e($buscar) . '»' : '' ?></caption>
  <thead><tr><th scope="col">Foto</th><th scope="col">Código</th><th scope="col">Producto</th><th scope="col">Precio</th><th scope="col">Stock</th><th scope="col">Acciones</th></tr></thead>
  <tbody>
  <?php foreach ($productos as $p): ?>
    <tr>
      <td><?php if (!empty($p['foto'])): ?><img src="/foto.php?f=<?= e($p['foto']) ?>" alt="<?= e($p['nombre']) ?>" width="64" height="64" loading="lazy"><?php endif; ?></td>
      <td><?= e($p['codigo']) ?></td>
      <td><?= e($p['nombre']) ?></td>
      <td class="num"><?= bs((float) $p['precio']) ?></td>
      <td class="num"><?= (int) $p['stock'] ?></td>
      <td>
        <a href="/editar.php?codigo=<?= urlencode($p['codigo']) ?>">Editar</a>
        <form method="post" action="/eliminar.php" class="en-linea">
          <input type="hidden" name="codigo" value="<?= e($p['codigo']) ?>">
          <button type="submit">Eliminar</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php require __DIR__ . '/../plantillas/pie.php'; ?>
