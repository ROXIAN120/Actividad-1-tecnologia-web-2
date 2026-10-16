<?php
declare(strict_types=1);
namespace App;
final class SocioRepositorio extends Repositorio
{
    public function todos(): array { return $this->consultar('SELECT id,nombre,comunidad,telefono FROM socios ORDER BY nombre,id')->fetchAll(); }
    public function porId(int $id): ?array { return $this->consultar('SELECT id,nombre,comunidad,telefono FROM socios WHERE id=?',[$id])->fetch() ?: null; }
    public function buscar(string $q = '', int $numero = 1): Pagina
    {
        $where=' WHERE (s.nombre LIKE :q OR s.comunidad LIKE :q2)'; $v=['q'=>self::patron($q),'q2'=>self::patron($q)];
        $total=(int)$this->consultar('SELECT COUNT(*) FROM socios s'.$where,$v)->fetchColumn();
        $numero=max(1,min($numero,max(1,(int)ceil($total/10))));
        $filas=$this->consultar('SELECT s.id,s.nombre,s.comunidad,s.telefono,COUNT(p.id) AS productos FROM socios s LEFT JOIN productos p ON p.socio_id=s.id'.$where.' GROUP BY s.id,s.nombre,s.comunidad,s.telefono ORDER BY s.nombre,s.id LIMIT :lim OFFSET :off',$v+['lim'=>10,'off'=>($numero-1)*10])->fetchAll();
        return new Pagina($filas,$total,$numero);
    }
    public function crear(array $d): int { $this->escribir('INSERT INTO socios (nombre,comunidad,telefono) VALUES (:nombre,:comunidad,:telefono)',$d); return (int)$this->bd->lastInsertId(); }
    public function actualizar(int $id,array $d): void
    {
        if (!$this->porId($id)) { throw new ErrorHttp(404,'No existe el socio.'); }
        $this->escribir('UPDATE socios SET nombre=:nombre,comunidad=:comunidad,telefono=:telefono WHERE id=:id',$d+['id'=>$id]);
    }
    public function eliminar(int $id): void { if ($this->escribir('DELETE FROM socios WHERE id=?',[$id])===0) { throw new ErrorHttp(404,'No existe el socio.'); } }
}
