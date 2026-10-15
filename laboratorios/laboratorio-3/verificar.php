<?php
// verificar.php — Comprueba el Laboratorio 3 (formularios, validación, PRG, JSON). Uso: php verificar.php
// Antes de empezar restaura datos/productos.json desde productos.inicial.json.
declare(strict_types=1);
require is_file(__DIR__ . '/Verificador.php') ? __DIR__ . '/Verificador.php' : __DIR__ . '/../../comun/Verificador.php';
copy(__DIR__ . '/datos/productos.inicial.json', __DIR__ . '/datos/productos.json');

$v = new Verificador();
$v->servidor(__DIR__ . '/public');
$bueno = ['codigo' => 'pol-100', 'nombre' => 'Polen seco 100 g', 'categoria' => 'Derivados', 'precio' => '32,50', 'stock' => '15', 'socio' => 'Élida Chuvé'];

$f = $v->pedir('/nuevo.php');
$v->revisar('GET /nuevo.php muestra el formulario con sus 6 campos', fn() =>
    $f['estado'] === 200 && substr_count($f['cuerpo'], 'name="') >= 6 && str_contains($f['cuerpo'], 'method="post"'));
$v->revisar('La cabecera y el pie se incluyen (require) en todas las páginas', fn() =>
    str_contains($f['cuerpo'], 'Registrar producto</a>') && str_contains($v->pedir('/')['cuerpo'], 'Tecnología Web II · UPDS'));

$malo = $v->pedir('/nuevo.php', 'POST', ['codigo' => 'miel', 'nombre' => 'ab', 'categoria' => 'Hongos', 'precio' => '-5', 'stock' => '2.5', 'socio' => '']);
$v->revisar('POST inválido → 422 con los 6 errores', fn() => $malo['estado'] === 422 && substr_count($malo['cuerpo'], 'class="error"') === 6);
$v->revisar('Los errores se asocian al campo (aria-invalid y aria-describedby)', fn() =>
    str_contains($malo['cuerpo'], 'aria-invalid="true"') && str_contains($malo['cuerpo'], 'aria-describedby="f-codigo-error"'));

$xss = $v->pedir('/nuevo.php', 'POST', ['nombre' => '"><script>alert(1)</script>', 'precio' => 'gratis'] + $bueno);
$v->revisar('Lo que escribió el usuario se conserva y se ESCAPA en el formulario', fn() =>
    str_contains($xss['cuerpo'], '&quot;&gt;&lt;script&gt;') && !str_contains($xss['cuerpo'], '<script>alert'));

$ok = $v->pedir('/nuevo.php', 'POST', $bueno);
$v->revisar('POST válido → 303 See Other hacia /?creado=POL-100 (patrón PRG)', fn() =>
    $ok['estado'] === 303 && preg_match('/^Location: \/\?creado=POL-100\r?$/mi', $ok['cabeceras']) === 1);
$json = json_decode((string) file_get_contents(__DIR__ . '/datos/productos.json'), true);
$ultimo = end($json);
$v->revisar('Se guardó normalizado en JSON (código en mayúsculas, 32,50 → 32.5, stock entero)', fn() =>
    count($json) === 7 && $ultimo['codigo'] === 'POL-100' && $ultimo['precio'] === 32.5 && $ultimo['stock'] === 15);
$v->revisar('El JSON guarda las tildes legibles (JSON_UNESCAPED_UNICODE)', fn() =>
    str_contains((string) file_get_contents(__DIR__ . '/datos/productos.json'), 'Élida Chuvé'));
$v->revisar('El catálogo muestra el mensaje y el producto nuevo', fn() =>
    str_contains($v->pedir('/?creado=POL-100')['cuerpo'], 'Producto POL-100 registrado') && str_contains($v->pedir('/')['cuerpo'], 'Polen seco 100 g'));
$v->revisar('Código repetido → error "Ya existe"', fn() => str_contains($v->pedir('/nuevo.php', 'POST', $bueno)['cuerpo'], 'Ya existe un producto con el código POL-100'));
$v->revisar('Búsqueda por GET (?q=cera) sin distinguir mayúsculas', fn() => str_contains($v->pedir('/?q=CERA')['cuerpo'], '1 productos para «CERA»'));
$lat = $v->pedir('/nuevo.php', 'POST', 'codigo=ABC-10&nombre=Polen+seco&categoria=Derivados&precio=5&stock=1&socio=%C9lida');
$v->revisar('Texto con codificación inválida (no UTF-8) → 422 sin error fatal', fn() =>
    $lat['estado'] === 422 && str_contains($lat['cuerpo'], 'codificación inválida') && !str_contains($lat['cuerpo'], 'Fatal error'));
$v->revisar('Un GET a /nuevo.php no modifica los datos', fn() => count(json_decode((string) file_get_contents(__DIR__ . '/datos/productos.json'), true)) === 7);
copy(__DIR__ . '/datos/productos.inicial.json', __DIR__ . '/datos/productos.json');
exit($v->fin());
