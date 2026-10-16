<?php
declare(strict_types=1);
namespace App;
use PDO;
use PDOException;
abstract class Repositorio
{
    public function __construct(protected readonly PDO $bd) {}
    protected function consultar(string $sql, array $valores = []): \PDOStatement
    {
        $c = $this->bd->prepare($sql);
        foreach ($valores as $clave => $valor) { $c->bindValue(is_int($clave) ? $clave + 1 : $clave, $valor, is_int($valor) ? PDO::PARAM_INT : ($valor === null ? PDO::PARAM_NULL : PDO::PARAM_STR)); }
        $c->execute();
        return $c;
    }
    protected function escribir(string $sql, array $valores): int
    {
        try { return $this->consultar($sql, $valores)->rowCount(); }
        catch (PDOException $e) {
            $codigo = (int) ($e->errorInfo[1] ?? 0);
            if (in_array($codigo, [1062,1451,1452,3819], true)) {
                throw new ErrorHttp(409, match ($codigo) {
                    1062 => 'Ya existe un registro con ese código.',
                    1451 => 'No se puede eliminar: existen productos asociados. Reasígnalos antes de continuar.',
                    1452 => 'La categoría o el socio relacionado ya no existe.',
                    default => 'Los datos incumplen una regla de integridad.',
                }, previous: $e);
            }
            throw $e;
        }
    }
    protected static function patron(string $q): string { return '%' . addcslashes($q, '%_\\') . '%'; }
}
