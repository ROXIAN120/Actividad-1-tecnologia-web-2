<?php
declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';
date_default_timezone_set('America/La_Paz');
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
$archivo=$argv[1]??__DIR__.'/migracion/origen_actividad01.json';
$aplicar=in_array('--aplicar',$argv,true);
$resultado=(new App\ImportadorJson(App\Conexion::crear()))->importar($archivo,$aplicar);
echo json_encode($resultado,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),PHP_EOL;
echo $aplicar?"Importación confirmada. No se sobrescriben códigos existentes.\n":"Simulación: se revirtieron los cambios. Usa --aplicar para confirmar.\n";
