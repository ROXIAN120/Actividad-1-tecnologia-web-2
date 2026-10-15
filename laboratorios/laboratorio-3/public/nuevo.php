<?php
// public/nuevo.php — Registrar un producto: GET muestra el formulario; POST valida, guarda y REDIRIGE (patrón PRG).
declare(strict_types=1);
require __DIR__ . '/../src/almacen.php';
require __DIR__ . '/../src/validacion.php';

$errores = [];
$datos = ['codigo' => '', 'nombre' => '', 'categoria' => '', 'precio' => '', 'stock' => '', 'socio' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = leer_productos();
    $resultado = validar_producto($_POST, $productos);
    $errores = $resultado['errores'];
    if ($errores === []) {
        $productos[] = $resultado['datos'];
        guardar_productos($productos);
        // PRG: al recargar el catálogo se repite el GET, no el registro.
        header('Location: /?creado=' . urlencode((string) $resultado['datos']['codigo']), true, 303);
        exit;
    }
    http_response_code(422);
    $datos = array_map('strval', $_POST + $datos);  // conservar lo que escribió (se escapa al mostrar)
}

$titulo = 'Registrar producto';
require __DIR__ . '/../plantillas/cabecera.php';

/** Campo de texto con su etiqueta, el valor anterior (escapado) y el mensaje de error. */
function campo(string $nombre, string $etiqueta, array $datos, array $errores, string $tipo = 'text', string $extra = ''): void
{
    $id = "f-$nombre";
    $error = $errores[$nombre] ?? '';
    echo "<p><label for=\"$id\">$etiqueta</label>";
    echo "<input id=\"$id\" name=\"$nombre\" type=\"$tipo\" value=\"" . e($datos[$nombre]) . "\" $extra"
        . ($error !== '' ? " aria-invalid=\"true\" aria-describedby=\"$id-error\"" : '') . '>';
    if ($error !== '') {
        echo "<span class=\"error\" id=\"$id-error\">" . e($error) . '</span>';
    }
    echo '</p>';
}
?>
<h2>Registrar producto</h2>
<?php if ($errores !== []): ?>
  <p class="alerta" role="alert">Revisa los <?= count($errores) ?> campos marcados.</p>
<?php endif; ?>
<form method="post" action="/nuevo.php" novalidate>
  <?php campo('codigo', 'Código (ej. MIE-500)', $datos, $errores, 'text', 'maxlength="8" required'); ?>
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
  <button type="submit">Guardar</button>
</form>
<?php require __DIR__ . '/../plantillas/pie.php'; ?>
