<?php
declare(strict_types=1);
// Pruebas de integración con fixtures propios. No contiene DROP ni restaura tablas.
require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set('America/La_Paz');
use App\{Conexion,ProductoRepositorio,SocioRepositorio,CategoriaRepositorio,Validacion,ErrorHttp};
$bd=Conexion::crear();$p=new ProductoRepositorio($bd);$s=new SocioRepositorio($bd);$c=new CategoriaRepositorio($bd);
$resultados=[];$fallos=0;$sociosPrueba=[];$productosPrueba=[];$etiqueta='Ensayo '.bin2hex(random_bytes(4));
function comprobar(string $nombre,callable $fn): void {global $resultados,$fallos;try {$ok=$fn()===true;$detalle='';} catch(Throwable $e) {$ok=false;$detalle=$e->getMessage();} $resultados[]=['prueba'=>$nombre,'ok'=>$ok,'detalle'=>$detalle];if(!$ok){$fallos++;}echo ($ok?'[OK] ':'[FALLO] ').$nombre.($detalle?' - '.$detalle:'').PHP_EOL;}
function espera(int $estado,callable $fn): bool {try{$fn();return false;}catch(ErrorHttp $e){return $e->estado===$estado;}}
$socket=stream_socket_server('tcp://127.0.0.1:0',$errno,$errstr);$direccion=stream_socket_get_name($socket,false);fclose($socket);$puerto=(int)substr(strrchr($direccion,':'),1);
$proceso=proc_open([PHP_BINARY,'-S','127.0.0.1:'.$puerto,'-t','public','public/index.php'],[['pipe','r'],['file',__DIR__.'/../datos/pruebas-servidor.log','a'],['file',__DIR__.'/../datos/pruebas-servidor.log','a']],$pipes,dirname(__DIR__));
if(!is_resource($proceso)){throw new RuntimeException('No se pudo iniciar servidor de pruebas.');}
$cookie='';
function pedir(string $ruta,string $metodo='GET',array $datos=[]): array {
 global $puerto,$cookie;
 $ch=curl_init('http://127.0.0.1:'.$puerto.$ruta);$cab=[];
 curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_TIMEOUT=>15,CURLOPT_CUSTOMREQUEST=>$metodo,CURLOPT_HEADERFUNCTION=>static function($ch,$linea)use(&$cab,&$cookie){if(str_contains($linea,':')){[$k,$v]=explode(':',$linea,2);$cab[strtolower(trim($k))]=trim($v);if(strtolower(trim($k))==='set-cookie'){$cookie=explode(';',trim($v))[0];}}return strlen($linea);}]);
 if($cookie!==''){curl_setopt($ch,CURLOPT_COOKIE,$cookie);}if($metodo==='POST'){curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datos));}
 $html=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);return ['estado'=>$status,'html'=>$html?:'','cabeceras'=>$cab];
}
function csrf(): string { $r=pedir('/socios/nuevo');preg_match('/name="csrf" value="([a-f0-9]+)"/',$r['html'],$m);return $m[1]??''; }
try {
 for($i=0;$i<50;$i++){if(@fsockopen('127.0.0.1',$puerto,$no,$msg,0.05)){break;}usleep(100000);}
 comprobar('PDO nativo y base MySQL 8.4',fn()=>$bd->getAttribute(PDO::ATTR_EMULATE_PREPARES)===false&&str_starts_with((string)$bd->getAttribute(PDO::ATTR_SERVER_VERSION),'8.4'));
 comprobar('Tres categorías normalizadas',fn()=>count($c->todos())===3);
 $antes=$p->buscar()->total;$antesSocios=$s->buscar()->total;
 comprobar('Datos iniciales coherentes: 36 productos y 8 socios',fn()=>$antes===36&&$antesSocios===8);
 comprobar('Página 4 contiene seis productos',fn()=>count($p->buscar(numero:4)->filas)===6);
 comprobar('Filtro Cera y vela encuentra dos',fn()=>$p->buscar('vela',3)->total===2);
 comprobar('Orden precio descendente',fn()=>$p->buscar(orden:'precio_desc')->filas[0]['codigo']==='CER-BLO');
 comprobar('Página grande se ajusta a última',fn()=>$p->buscar(numero:999)->numero===4);
 comprobar('Inyección SQL en productos no altera consulta',fn()=>$p->buscar("zzz' OR 1=1 -- -")->total===0);
 comprobar('Comodín porcentaje literal',fn()=>$p->buscar('%')->total===0);
 comprobar('Comilla sola se consulta sin error',fn()=>$p->buscar("'")->total===0);
 comprobar('Inyección SQL en socios no altera consulta',fn()=>$s->buscar("zzz' OR 1=1 -- -")->total===0);
 $base=$p->despues(0);$bloque=$p->despues((int)$base[9]['id']);
 comprobar('Paginación por clave no repite ID',fn()=>count($base)===11&&$bloque[0]['id']>$base[9]['id']);
 $id=$s->crear(Validacion::socio(['nombre'=>$etiqueta,'comunidad'=>'San Javier','telefono'=>'71234567']));$sociosPrueba[]=$id;
 comprobar('Crear socio con repositorio',fn()=>$s->porId($id)['nombre']===$etiqueta);
 $s->actualizar($id,Validacion::socio(['nombre'=>$etiqueta,'comunidad'=>'Roboré','telefono'=>'']));
 comprobar('Actualizar socio y teléfono nullable',fn()=>$s->porId($id)['comunidad']==='Roboré'&&$s->porId($id)['telefono']===null);
 comprobar('Socio inválido responde 422',fn()=>espera(422,fn()=>Validacion::socio(['nombre'=>'x','comunidad'=>'x','telefono'=>'<script>'])));
 for($i=0;$i<12;$i++){$sociosPrueba[]=$s->crear(['nombre'=>$etiqueta.' '.$i,'comunidad'=>'San Javier','telefono'=>null]);}
 comprobar('Socios paginados: página 2 con fixtures',fn()=>count($s->buscar($etiqueta,2)->filas)===3&&$s->buscar($etiqueta,2)->total===13);
 $codigo='TST-'.strtoupper(substr(bin2hex(random_bytes(3)),0,4));$d=Validacion::producto(['codigo'=>$codigo,'nombre'=>'Producto de ensayo','categoria_id'=>'1','precio'=>'32,5','stock'=>'15','socio_id'=>(string)$id],$c,$s);$p->crear($d);$productosPrueba[]=$codigo;
 comprobar('Crear producto: decimal normalizado y relaciones',fn()=>$p->porCodigo($codigo)['precio']==='32.50'&&$p->porCodigo($codigo)['categoria_id']===1);
 comprobar('Código repetido traduce 1062 a 409',fn()=>espera(409,fn()=>$p->crear($d)));
 $d['precio']='35.00';$p->actualizar($codigo,$d);
 comprobar('Actualizar producto solo afecta su código',fn()=>$p->porCodigo($codigo)['precio']==='35.00'&&$p->porCodigo('MIE-500')['precio']==='45.00');
 comprobar('Socio asociado: eliminación responde 409',fn()=>espera(409,fn()=>$s->eliminar($id)));
 comprobar('Producto inválido responde 422',fn()=>espera(422,fn()=>Validacion::producto(['codigo'=>'x','nombre'=>'x','precio'=>'-1','stock'=>[],'categoria_id'=>'999','socio_id'=>'999'],$c,$s)));
 comprobar('PDO FK impide relación inexistente',fn()=>espera(409,fn()=>$p->actualizar($codigo,array_replace($d,['categoria_id'=>999]))));
 comprobar('PDO CHECK impide precio negativo',fn()=>espera(409,fn()=>$p->actualizar($codigo,array_replace($d,['precio'=>'-1']))));
 $codigoJson='TST-'.strtoupper(substr(bin2hex(random_bytes(3)),0,4));$productosPrueba[]=$codigoJson;
 $archivoJson=tempnam(sys_get_temp_dir(),'tw2json');
 file_put_contents($archivoJson,json_encode([['codigo'=>$codigoJson,'nombre'=>'Miel importada JSON','categoria'=>'Miel','precio'=>29.5,'stock'=>2,'socio'=>$etiqueta]],JSON_THROW_ON_ERROR));
 try {
  $importador=new App\ImportadorJson($bd);$simulacion=$importador->importar($archivoJson);
  comprobar('Migración JSON: simulación revierte cambios',fn()=>$simulacion['creados']===1&&$p->porCodigo($codigoJson)===null);
  $migrado=$importador->importar($archivoJson,true);
  comprobar('Migración JSON: inserción confirmada',fn()=>$migrado['creados']===1&&$p->porCodigo($codigoJson)['precio']==='29.50');
  $repetido=$importador->importar($archivoJson,true);
  comprobar('Migración JSON idempotente sin sobrescribir',fn()=>$repetido['omitidos']===1&&$repetido['creados']===0);
  $p->eliminar($codigoJson);
 } finally {unlink($archivoJson);}
 $token=csrf();comprobar('Token CSRF generado',fn()=>strlen($token)===64);
 comprobar('POST sin token rechazado 403',fn()=>pedir('/socios','POST',['nombre'=>'Ataque'])['estado']===403);
 comprobar('GET no elimina y responde 405',fn()=>pedir('/socios/'.$id.'/eliminar')['estado']===405);
 comprobar('Ruta inexistente responde 404',fn()=>pedir('/inexistente')['estado']===404);
 comprobar('Página no numérica vuelve a 1',fn()=>str_contains(pedir('/productos?pagina=abc')['html'],'Página 1 de 4'));
 comprobar('Orden SQL desconocido no ejecuta instrucciones',fn()=>pedir('/productos?orden='.rawurlencode('p.precio; DROP TABLE productos'))['estado']===200&&$p->buscar()->total===$antes+1);
 $xss='<script>alert(1)</script>';
 comprobar('XSS reflejado escapado en búsqueda',fn()=>str_contains(pedir('/productos?q='.rawurlencode($xss))['html'],'&lt;script&gt;alert(1)&lt;/script&gt;')&&!str_contains(pedir('/productos?q='.rawurlencode($xss))['html'],$xss));
 $respuesta=pedir('/socios','POST',['csrf'=>$token,'nombre'=>$etiqueta.' HTTP','comunidad'=>$xss,'telefono'=>'']);
 $stmt=$bd->prepare('SELECT id FROM socios WHERE nombre=?');$stmt->execute([$etiqueta.' HTTP']);$httpId=(int)$stmt->fetchColumn();if($httpId){$sociosPrueba[]=$httpId;}
 comprobar('Alta HTTP socio usa PRG 303',fn()=>$respuesta['estado']===303&&$httpId>0);
 comprobar('XSS almacenado se muestra como texto',fn()=>str_contains(pedir('/socios/'.$httpId)['html'],'&lt;script&gt;alert(1)&lt;/script&gt;')&&!str_contains(pedir('/socios/'.$httpId)['html'],$xss));
 comprobar('Editar socio HTTP responde 303',fn()=>pedir('/socios/'.$httpId,'POST',['csrf'=>$token,'nombre'=>$etiqueta.' HTTP','comunidad'=>'Concepción','telefono'=>''])['estado']===303&&$s->porId($httpId)['comunidad']==='Concepción');
 comprobar('Validación HTTP socio conserva entrada escapada',fn()=>pedir('/socios','POST',['csrf'=>$token,'nombre'=>'x','comunidad'=>$xss])['estado']===422&&str_contains(pedir('/socios','POST',['csrf'=>$token,'nombre'=>'x','comunidad'=>$xss])['html'],'&lt;script&gt;'));
 comprobar('Validación HTTP producto responde 422',fn()=>pedir('/productos','POST',['csrf'=>$token,'codigo'=>'x'])['estado']===422);
 $codigoHttp='TST-'.strtoupper(substr(bin2hex(random_bytes(3)),0,4));$productosPrueba[]=$codigoHttp;
 $post=['csrf'=>$token,'codigo'=>$codigoHttp,'nombre'=>'Miel HTTP de prueba','categoria_id'=>'1','precio'=>'24,75','stock'=>'3','socio_id'=>(string)$id];
 comprobar('Crear producto HTTP 303',fn()=>pedir('/productos','POST',$post)['estado']===303&&$p->porCodigo($codigoHttp)['precio']==='24.75');
 comprobar('Editar producto HTTP 303',fn()=>pedir('/productos/'.$codigoHttp,'POST',array_replace($post,['precio'=>'27.00']))['estado']===303&&$p->porCodigo($codigoHttp)['precio']==='27.00');
 comprobar('Eliminar producto HTTP 303',fn()=>pedir('/productos/'.$codigoHttp.'/eliminar','POST',['csrf'=>$token])['estado']===303&&$p->porCodigo($codigoHttp)===null);
 comprobar('Eliminar socio HTTP 303',fn()=>pedir('/socios/'.$httpId.'/eliminar','POST',['csrf'=>$token])['estado']===303&&$s->porId($httpId)===null);
 $headers=pedir('/productos')['cabeceras'];
 comprobar('Cabeceras CSP y nosniff',fn()=>isset($headers['content-security-policy'])&&$headers['x-content-type-options']==='nosniff');
 comprobar('Configuración privada no accesible por HTTP',fn()=>pedir('/.env')['estado']===404&&pedir('/config/bd.php')['estado']===404);
 comprobar('Clave ID disponible por HTTP',fn()=>str_contains(pedir('/productos?modo=cursor')['html'],'sin COUNT ni OFFSET'));
 $p->eliminar($codigo);
 comprobar('Eliminar producto en repositorio',fn()=>$p->porCodigo($codigo)===null);
 comprobar('Eliminar inexistente responde 404',fn()=>espera(404,fn()=>$p->eliminar($codigo)));
} finally {
 foreach($productosPrueba as $codigo){$st=$bd->prepare('DELETE FROM productos WHERE codigo=?');$st->execute([$codigo]);}
 foreach($sociosPrueba as $id){$st=$bd->prepare('DELETE FROM socios WHERE id=?');$st->execute([$id]);}
 proc_terminate($proceso);proc_close($proceso);
}
comprobar('Fixtures limpiados sin restaurar tablas',fn()=>$p->buscar()->total===$antes&&$s->buscar()->total===$antesSocios);
$total=count($resultados);$texto='Resultado: '.($total-$fallos).' de '.$total.' comprobaciones';echo $texto.PHP_EOL;
if(!is_dir(__DIR__.'/../evidencias')){mkdir(__DIR__.'/../evidencias');}
file_put_contents(__DIR__.'/../evidencias/RESULTADOS_PRUEBAS.json',json_encode(['fecha'=>date(DATE_ATOM),'php'=>PHP_VERSION,'mysql'=>$bd->getAttribute(PDO::ATTR_SERVER_VERSION),'aprobadas'=>$total-$fallos,'total'=>$total,'pruebas'=>$resultados],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
exit($fallos?1:0);
