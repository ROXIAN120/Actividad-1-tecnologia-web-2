<?php
declare(strict_types=1);
namespace App;
final class Validacion
{
    public static function texto(array $d,string $k): string
    {
        $s=$d[$k]??'';
        return is_string($s)&&mb_check_encoding($s,'UTF-8') ? trim($s) : '';
    }
    public static function entero(mixed $v,int $min=1,int $max=1000000): int|false
    { return is_scalar($v) ? filter_var($v,FILTER_VALIDATE_INT,['options'=>['min_range'=>$min,'max_range'=>$max]]) : false; }
    public static function producto(array $d,CategoriaRepositorio $cats,SocioRepositorio $socios): array
    {
        $codigo=strtoupper(self::texto($d,'codigo')); $nombre=self::texto($d,'nombre');
        $precio=str_replace(',','.',self::texto($d,'precio')); $stock=self::entero($d['stock']??'',0,100000);
        $cat=self::entero($d['categoria_id']??''); $socio=self::entero($d['socio_id']??''); $e=[];
        if (!preg_match('/^[A-Z]{3}-[A-Z0-9]{2,4}$/D',$codigo)) {$e['codigo']='Usa un código como MIE-500 (AAA y 2–4 letras o números).';}
        if (mb_strlen($nombre)<3||mb_strlen($nombre)>80) {$e['nombre']='El nombre debe tener de 3 a 80 caracteres.';}
        if (!preg_match('/^(?:0|[1-9][0-9]{0,4})(?:\.[0-9]{1,2})?$/D',$precio)|| (float)$precio<0.01 || (float)$precio>10000) {$e['precio']='Precio entre 0,01 y 10.000, con hasta dos decimales.';}
        if ($stock===false) {$e['stock']='Stock entero entre 0 y 100.000.';}
        if ($cat===false||!$cats->existe($cat)) {$e['categoria_id']='Elige una categoría existente.';}
        if ($socio===false||!$socios->porId($socio)) {$e['socio_id']='Elige un socio existente.';}
        if ($e) {throw new ErrorHttp(422,'Revisa los campos marcados.',$e);}
        [$unidad,$decimal]=array_pad(explode('.',$precio,2),2,'');
        return ['codigo'=>$codigo,'nombre'=>$nombre,'categoria_id'=>$cat,'precio'=>$unidad.'.'.str_pad($decimal,2,'0'),'stock'=>$stock,'socio_id'=>$socio];
    }
    public static function socio(array $d): array
    {
        $n=self::texto($d,'nombre'); $c=self::texto($d,'comunidad'); $t=self::texto($d,'telefono'); $e=[];
        if (mb_strlen($n)<3||mb_strlen($n)>80) {$e['nombre']='Nombre de 3 a 80 caracteres.';}
        if (mb_strlen($c)<2||mb_strlen($c)>60) {$e['comunidad']='Comunidad de 2 a 60 caracteres.';}
        if ($t!==''&&!preg_match('/^[+0-9 ()-]{7,20}$/D',$t)) {$e['telefono']='Teléfono opcional, de 7 a 20 caracteres: números, +, espacios, paréntesis o guion.';}
        if ($e) {throw new ErrorHttp(422,'Revisa los campos marcados.',$e);}
        return ['nombre'=>$n,'comunidad'=>$c,'telefono'=>$t===''?null:$t];
    }
}
