<?php if ($errores): ?><p class="alerta" role="alert"><?= e($error) ?></p><?php endif ?>
<form action="<?= e($accion) ?>" method="post" class="formulario" novalidate><?= token() ?>
<?php
$campos=$tipo==='producto'?['codigo'=>'Código','nombre'=>'Nombre','categoria_id'=>'Categoría','precio'=>'Precio (Bs)','stock'=>'Stock','socio_id'=>'Socio productor']:['nombre'=>'Nombre','comunidad'=>'Comunidad','telefono'=>'Teléfono (opcional)'];
foreach ($campos as $clave=>$etiqueta): $valor=App\Validacion::texto($valores,$clave); if ($valor===''&&isset($valores[$clave])&&is_int($valores[$clave])) {$valor=(string)$valores[$clave];}
?>
<div><label for="<?= e($clave) ?>"><?= e($etiqueta) ?></label>
<?php if (in_array($clave,['categoria_id','socio_id'],true)): $opciones=$clave==='categoria_id'?$listaCategorias:$listaSocios; ?>
<select id="<?= e($clave) ?>" name="<?= e($clave) ?>" <?= isset($errores[$clave])?'aria-invalid="true" aria-describedby="error-'.e($clave).'"':'' ?>><option value="">Seleccionar</option><?php foreach ($opciones as $op): ?><option value="<?= e($op['id']) ?>" <?= (string)$op['id']===$valor?'selected':'' ?>><?= e($op['nombre']) ?></option><?php endforeach ?></select>
<?php else: ?><input id="<?= e($clave) ?>" name="<?= e($clave) ?>" value="<?= e($valor) ?>" maxlength="<?= $clave==='nombre'?80:($clave==='comunidad'?60:($clave==='telefono'?20:($clave==='codigo'?8:15))) ?>" <?= $clave==='codigo'&&!$nuevo?'readonly':'' ?> <?= isset($errores[$clave])?'aria-invalid="true" aria-describedby="error-'.e($clave).'"':'' ?>><?php endif ?>
<?php if (isset($errores[$clave])): ?><p id="error-<?= e($clave) ?>" class="error-campo"><?= e($errores[$clave]) ?></p><?php endif ?></div>
<?php endforeach ?><button><?= $nuevo?'Registrar':'Guardar cambios' ?></button><a href="<?= $tipo==='producto'?'/productos':'/socios' ?>">Cancelar</a></form>
