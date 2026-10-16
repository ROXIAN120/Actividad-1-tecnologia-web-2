<form method="get" class="filtros"><label>Buscar por nombre o comunidad<input name="q" value="<?= e($q) ?>" maxlength="150"></label><button>Buscar</button></form><p class="resumen"><?= e($pagina->rango()) ?></p>
<div class="tabla"><table><thead><tr><th>Socio</th><th>Comunidad</th><th>Teléfono</th><th>Productos</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($filas as $s): ?><tr><td><a href="/socios/<?= e($s['id']) ?>"><?= e($s['nombre']) ?></a></td><td><?= e($s['comunidad']) ?></td><td><?= e($s['telefono']??'Sin teléfono') ?></td><td><?= e($s['productos']) ?></td><td class="acciones"><a href="/socios/<?= e($s['id']) ?>/editar">Editar</a><form method="post" action="/socios/<?= e($s['id']) ?>/eliminar"><?= token() ?><button class="borrar">Eliminar</button></form></td></tr><?php endforeach ?>
<?php if (!$filas): ?><tr><td colspan="5">No se encontraron socios.</td></tr><?php endif ?></tbody></table></div>
<?php $params=['q'=>$q]; require __DIR__.'/paginacion.php'; ?>
<p class="nota">La integridad referencial impide eliminar un socio con productos asociados.</p>
