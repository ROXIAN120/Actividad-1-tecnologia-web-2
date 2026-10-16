<?php
declare(strict_types=1);
require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set('America/La_Paz');
$bd=App\Conexion::crear();
$bd->prepare('ANALYZE TABLE productos')->execute();
$consultas=[
 'socio_sin_indice'=>'SELECT id,nombre FROM productos IGNORE INDEX (idx_producto_socio_nombre) WHERE socio_id=3 ORDER BY nombre,id LIMIT 10',
 'socio_con_indice'=>'SELECT id,nombre FROM productos WHERE socio_id=3 ORDER BY nombre,id LIMIT 10',
 'pagina_offset'=>'SELECT id,nombre FROM productos ORDER BY id LIMIT 10 OFFSET 20',
 'pagina_clave'=>'SELECT id,nombre FROM productos WHERE id>20 ORDER BY id LIMIT 10'
];
$salida=['fecha'=>date(DATE_ATOM),'mysql'=>$bd->getAttribute(PDO::ATTR_SERVER_VERSION),'repeticiones'=>31,'nota'=>'Datos educativos: 36 filas. Medianas locales, no benchmark de carga. IGNORE INDEX compara planes sin cambiar el esquema.','consultas'=>[]];
foreach($consultas as $nombre=>$sql){
 $exp=$bd->prepare('EXPLAIN '.$sql);$exp->execute();$plan=$exp->fetchAll();
 $ana=$bd->prepare('EXPLAIN ANALYZE '.$sql);$ana->execute();$real=$ana->fetchAll(PDO::FETCH_COLUMN);
 $c=$bd->prepare($sql);$tiempos=[];for($i=0;$i<31;$i++){$inicio=hrtime(true);$c->execute();$c->fetchAll();$tiempos[]=(hrtime(true)-$inicio)/1e6;}sort($tiempos);
 $salida['consultas'][$nombre]=['sql'=>$sql,'explain'=>$plan,'analyze'=>$real,'mediana_ms'=>round($tiempos[15],4)];
}
file_put_contents(__DIR__.'/../evidencias/EXPLAIN_MEDICIONES.json',json_encode($salida,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
echo json_encode($salida,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),PHP_EOL;
