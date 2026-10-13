<?php
// plantillas/formulario.php — Formulario de producto compartido por nuevo.php y editar.php (include).
// Variables que debe definir la página: $accion, $datos, $errores, $editando (bool)
declare(strict_types=1);

/** Campo de texto con etiqueta, valor anterior (escapado) y error asociado. */
function campo(string $nombre, string $etiqueta, array $datos, array $errores, string $tipo = 'text', string $extra = ''): void
{
    $id = "f-$nombre";
    $error = $errores[$nombre] ?? '';
    echo "<p><label for=\"$id\">$etiqueta</label>";
    echo "<input id=\"$id\" name=\"$nombre\" type=\"$tipo\" value=\"" . e((string) $datos[$nombre]) . "\" $extra"
        . ($error !== '' ? " aria-invalid=\"true\" aria-describedby=\"$id-error\"" : '') . '>';
    if ($error !== '') {
        echo "<span class=\"error\" id=\"$id-error\">" . e($error) . '</span>';
    }
    echo '</p>';
}
?>
<?php if ($errores !== []): ?>
  <p class="alerta" role="alert">Revisa los <?= count($errores) ?> campos marcados.</p>
<?php endif; ?>
<!-- enctype multipart/form-data: OBLIGATORIO para enviar archivos -->
<form method="post" action="<?= e($accion) ?>" enctype="multipart/form-data" novalidate>
  <?php campo('codigo', 'Código (ej. MIE-500)', $datos, $errores, 'text', 'maxlength="8" required' . ($editando ? ' readonly' : '')); ?>
  <?php campo('nombre', 'Nombre', $datos, $errores, 'text', 'maxlength="80" required'); ?>
  <p>
    <label for="f-categoria">Categoría</label>
    <select id="f-categoria" name="categoria">
      <option value="">— elegir —</option>
      <?php foreach (CATEGORIAS as $c): ?>
        <option value="<?= e($c) ?>"<?= $datos['categoria'] === $c ? ' selected' : '' ?>><?= e($c) ?></option>
      <?php endforeach; ?>
    </select>
    <?php if (isset($errores['categoria'])): ?><span class="error"><?= e($errores['categoria']) ?></span><?php endif; ?>
  </p>
  <?php campo('precio', 'Precio (Bs)', $datos, $errores, 'text', 'inputmode="decimal" required'); ?>
  <?php campo('stock', 'Stock (unidades)', $datos, $errores, 'number', 'min="0" required'); ?>
  <?php campo('socio', 'Socio productor', $datos, $errores, 'text', 'required'); ?>
  <p>
    <label for="f-foto">Foto (JPG, PNG o WebP, hasta 2 MB)</label>
    <input id="f-foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"<?= isset($errores['foto']) ? ' aria-invalid="true" aria-describedby="f-foto-error"' : '' ?>>
    <?php if (isset($errores['foto'])): ?><span class="error" id="f-foto-error"><?= e($errores['foto']) ?></span><?php endif; ?>
  </p>
  <button type="submit"><?= $editando ? 'Guardar cambios' : 'Registrar' ?></button>
</form>
