<?php
declare(strict_types=1);
namespace App;
final class ProductoRepositorio extends Repositorio
{
    private const SELECT = 'SELECT p.*, c.nombre AS categoria, s.nombre AS socio FROM productos p JOIN categorias c ON c.id=p.categoria_id JOIN socios s ON s.id=p.socio_id';
    public const ORDENES = ['nombre'=>'Nombre A–Z','precio'=>'Precio menor a mayor','precio_desc'=>'Precio mayor a menor','stock'=>'Stock menor a mayor'];
    private const SQL_ORDEN = ['nombre'=>'p.nombre, p.id','precio'=>'p.precio, p.id','precio_desc'=>'p.precio DESC, p.id','stock'=>'p.stock, p.id'];
    private function filtros(string $q, int $categoria): array
    {
        $where = ' WHERE (p.nombre LIKE :q OR p.codigo LIKE :q2)';
        $valores = ['q'=>self::patron($q),'q2'=>self::patron($q)];
        if ($categoria > 0) { $where .= ' AND p.categoria_id=:cat'; $valores['cat']=$categoria; }
        return [$where,$valores];
    }
    public function buscar(string $q = '', int $categoria = 0, string $orden = 'nombre', int $numero = 1): Pagina
    {
        [$where,$v]=$this->filtros($q,$categoria);
        $total=(int)$this->consultar('SELECT COUNT(*) FROM productos p'.$where,$v)->fetchColumn();
        $numero=max(1,min($numero,max(1,(int)ceil($total/10))));
        $ordenSql=self::SQL_ORDEN[$orden]??self::SQL_ORDEN['nombre'];
        $filas=$this->consultar(self::SELECT.$where.' ORDER BY '.$ordenSql.' LIMIT :lim OFFSET :off',$v+['lim'=>10,'off'=>($numero-1)*10])->fetchAll();
        return new Pagina($filas,$total,$numero);
    }
    /** Paginación por clave ID: sin OFFSET ni COUNT. El orden se fija por ID. */
    public function despues(int $id, string $q = '', int $categoria = 0): array
    {
        [$where,$v]=$this->filtros($q,$categoria);
        return $this->consultar(self::SELECT.$where.' AND p.id > :id ORDER BY p.id LIMIT :lim',$v+['id'=>$id,'lim'=>11])->fetchAll();
    }
    public function porCodigo(string $codigo): ?array { return $this->consultar(self::SELECT.' WHERE p.codigo=?',[$codigo])->fetch() ?: null; }
    public function deSocio(int $id): array { return $this->consultar(self::SELECT.' WHERE p.socio_id=? ORDER BY p.nombre, p.id',[$id])->fetchAll(); }
    public function crear(array $d): void { $this->escribir('INSERT INTO productos (codigo,nombre,categoria_id,precio,stock,socio_id) VALUES (:codigo,:nombre,:categoria_id,:precio,:stock,:socio_id)',$d); }
    public function actualizar(string $codigo,array $d): void
    {
        if (!$this->porCodigo($codigo)) { throw new ErrorHttp(404,'No existe el producto.'); }
        $this->escribir('UPDATE productos SET nombre=:nombre,categoria_id=:categoria_id,precio=:precio,stock=:stock,socio_id=:socio_id WHERE codigo=:codigo',['codigo'=>$codigo]+$d);
    }
    public function eliminar(string $codigo): void { if ($this->escribir('DELETE FROM productos WHERE codigo=?',[$codigo])===0) { throw new ErrorHttp(404,'No existe el producto.'); } }
}
