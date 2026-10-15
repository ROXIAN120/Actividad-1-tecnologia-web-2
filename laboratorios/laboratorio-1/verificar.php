<?php
// verificar.php — Comprueba el Laboratorio 1.  Uso:  php verificar.php
declare(strict_types=1);
require is_file(__DIR__ . '/Verificador.php') ? __DIR__ . '/Verificador.php' : __DIR__ . '/../../comun/Verificador.php';

$v = new Verificador();
$v->servidor(__DIR__ . '/public');
$r = $v->pedir('/');
$html = $r['cuerpo'];
$datos = require __DIR__ . '/datos.php';

$v->revisar('La página responde 200 generada por PHP', fn() => $r['estado'] === 200 && str_contains($r['cabeceras'], 'X-Powered-By: PHP'));
$v->revisar('No se ve código PHP en la respuesta', fn() => !str_contains($html, '<?php') && !str_contains($html, '<?='));
$v->revisar('Muestra los 6 productos de datos.php (una fila por producto)', fn() => substr_count($html, '<tr') === count($datos) + 1);
$v->revisar('Encabezado con totales: 6 productos · 5 disponibles', fn() => str_contains($html, '6 productos · 5 disponibles'));
$v->revisar('Precios en formato boliviano (Bs 55,50 y Bs 120,00)', fn() => str_contains($html, 'Bs 55,50') && str_contains($html, 'Bs 120,00'));
$v->revisar('Producto sin stock marcado como Agotado', fn() => str_contains($html, 'Agotado') && str_contains($html, 'class="agotado"'));
$v->revisar('El "&" del nombre se escapa (&amp;)', fn() => str_contains($html, 'Polen &amp; propóleo'));
$v->revisar('La etiqueta <b> de un dato NO se interpreta (se escapa)', fn() => str_contains($html, 'Cera &lt;b&gt;pura&lt;/b&gt;') && !str_contains($html, 'Cera <b>'));
$v->revisar('Fecha y hora del servidor en el pie (zona America/La_Paz)', fn() => preg_match('/el \d{2}\/\d{2}\/\d{4} a las \d{2}:\d{2}/', $html) === 1);
// Ojo: php -S responde index.php para rutas inexistentes (200), así que se comprueba que los DATOS no aparezcan
$v->revisar('datos.php está FUERA de public/ (su contenido no se puede pedir por URL)', fn() => !str_contains($v->pedir('/datos.php')['cuerpo'], "'codigo' =>") && !str_contains($v->pedir('/../datos.php')['cuerpo'], 'return ['));
$v->revisar('La hoja de estilos se sirve (200)', fn() => $v->pedir('/css/estilo.css')['estado'] === 200);
$v->revisar('Sintaxis PHP válida (php -l)', fn() => str_contains((string) shell_exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg(__DIR__ . '/public/index.php')), 'No syntax errors'));
exit($v->fin());
