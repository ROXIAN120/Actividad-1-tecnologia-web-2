<?php
// verificar.php — Comprueba el Laboratorio 4 (fotos, editar, eliminar). Uso: php verificar.php
// Restaura los datos y vacía datos/fotos al empezar y al terminar.
declare(strict_types=1);
require is_file(__DIR__ . '/Verificador.php') ? __DIR__ . '/Verificador.php' : __DIR__ . '/../../comun/Verificador.php';

function restaurar(): void
{
    copy(__DIR__ . '/datos/productos.inicial.json', __DIR__ . '/datos/productos.json');
    foreach (glob(__DIR__ . '/datos/fotos/*.*') as $f) {
        unlink($f);
    }
}
// clearstatcache(): PHP guarda en caché el resultado de is_file(); el servidor borra archivos en OTRO proceso
function fotos(): array { clearstatcache(); return glob(__DIR__ . '/datos/fotos/*.*'); }
function productos(): array { return json_decode((string) file_get_contents(__DIR__ . '/datos/productos.json'), true); }
function buscar(string $codigo): ?array
{
    foreach (productos() as $p) {
        if ($p['codigo'] === $codigo) {
            return $p;
        }
    }
    return null;
}

restaurar();
$tmp = sys_get_temp_dir();
// Imagen PNG real de 1×1 píxel, un "PHP disfrazado de JPG" y un archivo de más de 2 MB
file_put_contents("$tmp/panal.png", base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='));
file_put_contents("$tmp/foto.jpg", "<?php echo 'hola'; ?>");
file_put_contents("$tmp/grande.png", base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==') . str_repeat("\0", 2_200_000));
$png = fn() => new CURLFile("$tmp/panal.png", 'image/png', 'panal.png');
$base = ['codigo' => 'POL-100', 'nombre' => 'Polen seco 100 g', 'categoria' => 'Derivados', 'precio' => '32,50', 'stock' => '15', 'socio' => 'Élida Chuvé'];

$v = new Verificador();
$v->servidor(__DIR__ . '/public');

$v->revisar('El formulario envía archivos (enctype="multipart/form-data")', fn() =>
    str_contains($v->pedir('/nuevo.php')['cuerpo'], 'enctype="multipart/form-data"'));
$ok = $v->pedir('/nuevo.php', 'POST', $base + ['foto' => $png()]);
$p = buscar('POL-100');
$v->revisar('Registro con foto PNG → 303 y nombre aleatorio (24 hex + extensión)', fn() =>
    $ok['estado'] === 303 && $p !== null && preg_match('/^[a-f0-9]{24}\.png$/', (string) $p['foto']) === 1);
$v->revisar('La foto se guardó en datos/fotos (fuera de public/)', fn() => count(fotos()) === 1 && is_file(__DIR__ . '/datos/fotos/' . $p['foto']));
$v->revisar('El catálogo muestra la foto con texto alternativo', fn() =>
    str_contains($v->pedir('/')['cuerpo'], '<img src="/foto.php?f=' . $p['foto'] . '" alt="Polen seco 100 g"'));
$f = $v->pedir('/foto.php?f=' . $p['foto']);
$v->revisar('foto.php entrega la imagen con su tipo real y nosniff', fn() =>
    $f['estado'] === 200 && str_contains($f['cabeceras'], 'Content-Type: image/png') && stripos($f['cabeceras'], 'X-Content-Type-Options: nosniff') !== false
    && str_starts_with($f['cuerpo'], "\x89PNG"));
$falso = $v->pedir('/nuevo.php', 'POST', ['codigo' => 'ABC-10'] + $base + ['foto' => new CURLFile("$tmp/foto.jpg", 'image/jpeg', 'foto.jpg')]);
$v->revisar('Un PHP disfrazado de foto.jpg (type image/jpeg) se rechaza → 422', fn() =>
    $falso['estado'] === 422 && str_contains($falso['cuerpo'], 'no es una imagen') && count(fotos()) === 1);
$grande = $v->pedir('/nuevo.php', 'POST', ['codigo' => 'ABC-11'] + $base + ['foto' => new CURLFile("$tmp/grande.png", 'image/png', 'grande.png')]);
$v->revisar('Una imagen de más de 2 MB se rechaza', fn() => $grande['estado'] === 422 && str_contains($grande['cuerpo'], 'class="error" id="f-foto-error">La foto supera'));
$v->revisar('foto.php no permite salir de la carpeta (../)', fn() =>
    $v->pedir('/foto.php?f=../productos.json')['estado'] === 404 && $v->pedir('/foto.php?f=..%2F..%2Fsrc%2Ffotos.php')['estado'] === 404);
$huerfana = $v->pedir('/nuevo.php', 'POST', ['codigo' => 'ABC-12', 'precio' => 'gratis'] + $base + ['foto' => $png()]);
$v->revisar('Si otro campo falla, la foto subida no queda huérfana', fn() => $huerfana['estado'] === 422 && count(fotos()) === 1);

$ed = $v->pedir('/editar.php?codigo=POL-100', 'POST', ['precio' => '35'] + $base);
$v->revisar('Editar el precio → 303 y la foto se conserva', fn() =>
    $ed['estado'] === 303 && buscar('POL-100')['precio'] === 35.0 && buscar('POL-100')['foto'] === $p['foto']);
$fotoVieja = $p['foto'];
$v->pedir('/editar.php?codigo=POL-100', 'POST', $base + ['foto' => $png()]);
$v->revisar('Una foto nueva reemplaza a la anterior (la vieja se borra)', fn() =>
    buscar('POL-100')['foto'] !== $fotoVieja && count(fotos()) === 1 && !is_file(__DIR__ . "/datos/fotos/$fotoVieja"));
$v->revisar('Eliminar por GET no está permitido (405)', fn() => $v->pedir('/eliminar.php?codigo=POL-100')['estado'] === 405 && buscar('POL-100') !== null);
$el = $v->pedir('/eliminar.php', 'POST', ['codigo' => 'POL-100']);
$v->revisar('Eliminar por POST → 303, desaparece el producto y su foto', fn() =>
    $el['estado'] === 303 && buscar('POL-100') === null && count(fotos()) === 0);
restaurar();
exit($v->fin());
