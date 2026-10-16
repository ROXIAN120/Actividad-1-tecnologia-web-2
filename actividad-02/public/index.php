<?php
declare(strict_types=1);
// Único punto de entrada HTTP. Configuración y datos quedan fuera de public.
if (PHP_SAPI==='cli-server') {
    $archivo=realpath(__DIR__.rawurldecode((string)parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH)));
    if ($archivo!==false && str_starts_with($archivo,__DIR__.DIRECTORY_SEPARATOR) && is_file($archivo) && pathinfo($archivo,PATHINFO_EXTENSION)==='css') {return false;}
}
require __DIR__.'/../vendor/autoload.php';
use App\{Conexion,ProductoRepositorio,SocioRepositorio,CategoriaRepositorio,Pagina,Validacion,ErrorHttp};
ini_set('display_errors','0'); date_default_timezone_set('America/La_Paz');
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')]);
session_start();
$_SESSION['csrf']??=bin2hex(random_bytes(32));
header('X-Content-Type-Options: nosniff'); header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'none'; object-src 'none'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'");
function e(mixed $v): string {return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function bs(mixed $v): string {return 'Bs '.number_format((float)$v,2,',','.');}
function url(string $ruta,array $params=[]): string {return $ruta.($params?'?'.http_build_query($params):'');}
function token(): string {return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">';}
function redirigir(string $ruta): never {$_SESSION['mensaje']='Operación completada correctamente.'; header('Location: '.$ruta,true,303); exit;}
$ruta=rawurldecode((string)parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
$metodo=$_SERVER['REQUEST_METHOD']; $vista='error'; $titulo='Error'; $errores=[]; $valores=[]; $tipo=''; $nuevo=true; $accion=''; $error=''; $estado=200;
try {
    // Resolver ruta y método antes de tocar la base o comprobar CSRF.
    $rutas=[
        '#^/$#'=>['GET'], '#^/productos$#'=>['GET','POST'], '#^/productos/nuevo$#'=>['GET'],
        '#^/productos/([A-Z]{3}-[A-Z0-9]{2,4})/editar$#'=>['GET'], '#^/productos/([A-Z]{3}-[A-Z0-9]{2,4})/eliminar$#'=>['POST'], '#^/productos/([A-Z]{3}-[A-Z0-9]{2,4})$#'=>['POST'],
        '#^/socios$#'=>['GET','POST'], '#^/socios/nuevo$#'=>['GET'], '#^/socios/([1-9][0-9]{0,8})/editar$#'=>['GET'], '#^/socios/([1-9][0-9]{0,8})/eliminar$#'=>['POST'], '#^/socios/([1-9][0-9]{0,8})$#'=>['GET','POST']
    ];
    $permitidos=null; $coincide=[];
    foreach ($rutas as $patron=>$metodos) {if (preg_match($patron,$ruta,$m)) {$permitidos=$metodos;$coincide=$m;break;}}
    if ($permitidos===null) {throw new ErrorHttp(404,'La ruta solicitada no existe.');}
    if (!in_array($metodo,$permitidos,true)) {header('Allow: '.implode(', ',$permitidos));throw new ErrorHttp(405,'Método no permitido.');}
    if ($metodo==='POST' && (!is_string($_POST['csrf']??null)||!hash_equals($_SESSION['csrf'],$_POST['csrf']))) {throw new ErrorHttp(403,'El formulario caducó o no es válido. Recarga e inténtalo otra vez.');}
    if ($ruta==='/') {header('Location: /productos',true,302);exit;}
    $bd=Conexion::crear();$productos=new ProductoRepositorio($bd);$socios=new SocioRepositorio($bd);$categorias=new CategoriaRepositorio($bd);
    $q=mb_substr(Validacion::texto($_GET,'q'),0,150); $numero=Validacion::entero($_GET['pagina']??1)?:1;
    if ($metodo==='GET' && $ruta==='/productos') {
        $titulo='Productos';$vista='productos';$cat=Validacion::entero($_GET['categoria']??0,0)?:0;
        $orden=Validacion::texto($_GET,'orden'); $orden=array_key_exists($orden,ProductoRepositorio::ORDENES)?$orden:'nombre';
        $cursor=Validacion::texto($_GET,'modo')==='cursor';
        if ($cursor) {$despues=Validacion::entero($_GET['despues']??0,0)?:0;$filas=$productos->despues($despues,$q,$cat);$hayMas=count($filas)>10;$filas=array_slice($filas,0,10);}
        else {$pagina=$productos->buscar($q,$cat,$orden,$numero);$filas=$pagina->filas;}
        $listaCategorias=$categorias->todos();
    } elseif ($metodo==='GET' && $ruta==='/socios') {
        $titulo='Socios';$vista='socios';$pagina=$socios->buscar($q,$numero);$filas=$pagina->filas;
    } elseif ($metodo==='GET' && preg_match('#^/socios/([0-9]+)$#',$ruta,$m)) {
        $socio=$socios->porId((int)$m[1])??throw new ErrorHttp(404,'No existe el socio.'); $filas=$productos->deSocio((int)$m[1]);$titulo=$socio['nombre'];$vista='socio';
    } else {
        $tipo=str_starts_with($ruta,'/productos')?'producto':'socio';$nuevo=in_array($ruta,['/productos','/productos/nuevo','/socios','/socios/nuevo'],true);
        $id=$nuevo?null:($coincide[1]??null);$titulo=($nuevo?'Registrar ':'Editar ').$tipo;$accion=$tipo==='producto'?'/productos':'/socios';
        if (!$nuevo) {$accion.='/'.rawurlencode((string)$id);$valores=$tipo==='producto'?($productos->porCodigo((string)$id)??throw new ErrorHttp(404,'No existe el producto.')):($socios->porId((int)$id)??throw new ErrorHttp(404,'No existe el socio.'));}
        if ($metodo==='POST'&&str_ends_with($ruta,'/eliminar')) {if ($tipo==='producto') {$productos->eliminar((string)$id);} else {$socios->eliminar((int)$id);} redirigir($tipo==='producto'?'/productos':'/socios');}
        $vista='formulario';
        if ($tipo==='producto') {$listaCategorias=$categorias->todos();$listaSocios=$socios->todos();}
        if ($metodo==='POST') {
            $valores=$_POST; if (!$nuevo && $tipo==='producto') {$valores['codigo']=$id;}
            try {
                if ($tipo==='producto') {$d=Validacion::producto($valores,$categorias,$socios);if ($nuevo) {$productos->crear($d);} else {$productos->actualizar((string)$id,$d);}}
                else {$d=Validacion::socio($valores);if ($nuevo) {$socios->crear($d);} else {$socios->actualizar((int)$id,$d);}}
                redirigir($tipo==='producto'?'/productos':'/socios');
            } catch (ErrorHttp $ex) {if ($ex->estado!==422) {throw $ex;} http_response_code(422);$errores=$ex->errores;$error=$ex->getMessage();}
        }
    }
} catch (ErrorHttp $ex) {$estado=$ex->estado;http_response_code($estado);$vista='error';$titulo='No se puede completar';$error=$ex->getMessage();}
catch (Throwable $ex) {
    $estado=500;http_response_code(500);$vista='error';$titulo='Error interno';$ref=bin2hex(random_bytes(4));
    error_log('['.date('c')."] ref=$ref ".$ex::class.': '.$ex->getMessage()."\n",3,__DIR__.'/../datos/errores.log');
    $error='Registramos el problema. Contacta con soporte indicando la referencia '.$ref.'.';
}
$mensaje=$_SESSION['mensaje']??'';unset($_SESSION['mensaje']);
require __DIR__.'/../plantillas/base.php';
