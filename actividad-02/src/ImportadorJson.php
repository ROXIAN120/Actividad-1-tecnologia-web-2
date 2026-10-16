<?php
declare(strict_types=1);
namespace App;
use PDO;
final class ImportadorJson
{
    public function __construct(private readonly PDO $bd) {}
    /** No sobrescribe productos existentes. Toda la importación es atómica. */
    public function importar(string $archivo,bool $aplicar=false): array
    {
        $filas=json_decode((string)file_get_contents($archivo),true,512,JSON_THROW_ON_ERROR);
        if(!is_array($filas)||!array_is_list($filas)){throw new \InvalidArgumentException('El JSON debe ser una lista de productos.');}
        $p=new ProductoRepositorio($this->bd);$s=new SocioRepositorio($this->bd);$c=new CategoriaRepositorio($this->bd);
        $mapa=[];foreach($c->todos() as $categoria){$mapa[$categoria['nombre']]=$categoria['id'];}
        $r=['leidos'=>count($filas),'creados'=>0,'omitidos'=>0,'socios_nuevos'=>0,'aplicado'=>$aplicar];
        $this->bd->beginTransaction();
        try {
            foreach($filas as $f){
                if(!is_array($f)){throw new \InvalidArgumentException('Fila JSON inválida.');}
                $codigo=strtoupper(Validacion::texto($f,'codigo'));
                if($p->porCodigo($codigo)){$r['omitidos']++;continue;}
                $cat=$mapa[Validacion::texto($f,'categoria')]??throw new \InvalidArgumentException('Categoría JSON desconocida.');
                $nombre=Validacion::texto($f,'socio');
                if($nombre===''){throw new \InvalidArgumentException('Falta socio en el JSON.');}
                $q=$this->bd->prepare('SELECT id FROM socios WHERE nombre=? ORDER BY id LIMIT 1');$q->execute([$nombre]);$id=$q->fetchColumn();
                if($id===false){$id=$s->crear(Validacion::socio(['nombre'=>$nombre,'comunidad'=>Validacion::texto($f,'comunidad')?:'Sin especificar','telefono'=>'']));$r['socios_nuevos']++;}
                $d=['codigo'=>$codigo,'nombre'=>Validacion::texto($f,'nombre'),'categoria_id'=>(string)$cat,'precio'=>(string)($f['precio']??''),'stock'=>(string)($f['stock']??''),'socio_id'=>(string)$id];
                $p->crear(Validacion::producto($d,$c,$s));$r['creados']++;
            }
            if($aplicar){$this->bd->commit();}else{$this->bd->rollBack();}
            return $r;
        }catch(\Throwable $e){if($this->bd->inTransaction()){$this->bd->rollBack();}throw $e;}
    }
}
