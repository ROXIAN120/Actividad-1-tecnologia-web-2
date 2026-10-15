<?php
// src/BaseDatos/ProductoConsultas.php — TODO el SQL de productos en un solo lugar, SIEMPRE con consultas preparadas.
// (En el laboratorio 9 esta clase se convierte en un Repositorio que devuelve objetos Producto.)
declare(strict_types=1);

namespace Apicultores\BaseDatos;

use Apicultores\Excepciones\Conflicto;
use Apicultores\Excepciones\NoEncontrado;
use PDO;
use PDOException;

final class ProductoConsultas
{
    private const SELECCION = 'SELECT p.id, p.codigo, p.nombre, p.categoria, p.precio, p.stock, p.socio_id,
                                      s.nombre AS socio, s.comunidad
                               FROM productos p
                               JOIN socios s ON s.id = p.socio_id';

    public function __construct(private readonly PDO $bd)
    {
    }

    /** Búsqueda por nombre o código. El texto del usuario NUNCA se pega dentro del SQL. */
    public function buscar(string $texto = ''): array
    {
        // TODO 1: ¡PELIGRO! El texto del usuario se pega DENTRO del SQL (inyección SQL).
        //         Reescríbelo con prepare() + execute() y marcadores :patron y :patron2
        //         (con la emulación desactivada, un mismo marcador no puede repetirse).
        //         El patrón es '%' . self::escaparComodines($texto) . '%'.
        $consulta = $this->bd->prepare(self::SELECCION .
            ' WHERE p.nombre LIKE :patron OR p.codigo LIKE :patron2 ORDER BY p.categoria, p.nombre');
        $patron = '%' . self::escaparComodines($texto) . '%';
        $consulta->execute(['patron' => $patron, 'patron2' => $patron]);
        return $consulta->fetchAll();
    }

    /** En LIKE, % y _ son comodines: si el usuario los escribe, deben buscarse literalmente. */
    public static function escaparComodines(string $texto): string
    {
        // TODO 2: antepone una barra invertida a %, _ y la propia barra (pista: addcslashes). Hoy "%" devuelve TODO.
        return addcslashes($texto, '%_\\');
    }

    public function porCodigo(string $codigo): ?array
    {
        $consulta = $this->bd->prepare(self::SELECCION . ' WHERE p.codigo = :codigo');
        $consulta->execute(['codigo' => $codigo]);
        return $consulta->fetch() ?: null;
    }

    public function existeCodigo(string $codigo): bool
    {
        $consulta = $this->bd->prepare('SELECT COUNT(*) FROM productos WHERE codigo = ?');
        $consulta->execute([$codigo]);
        return (int) $consulta->fetchColumn() > 0;
    }

    /** @return array<int, string> id => nombre de los socios (para el <select>) */
    public function socios(): array
    {
        return $this->bd->query('SELECT id, nombre FROM socios ORDER BY nombre')->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function insertar(array $d): int
    {
        // TODO 3: si MySQL responde el error 1062 (UNIQUE duplicado), lanza Conflicto con $previous.
        //         Pista: try/catch (PDOException $e) y $e->errorInfo[1]. Cualquier otro error: throw $e;
        $consulta = $this->bd->prepare(
            'INSERT INTO productos (codigo, nombre, categoria, precio, stock, socio_id)
             VALUES (:codigo, :nombre, :categoria, :precio, :stock, :socio_id)');
        try {
            $consulta->execute([
            'codigo' => $d['codigo'], 'nombre' => $d['nombre'], 'categoria' => $d['categoria'],
            'precio' => $d['precio'], 'stock' => $d['stock'], 'socio_id' => $d['socio_id'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new Conflicto("Ya existe un producto con el código {$d['codigo']}.", 0, $e);
            }
            throw $e;
        }
        return (int) $this->bd->lastInsertId();
    }

    public function actualizar(string $codigo, array $d): void
    {
        $consulta = $this->bd->prepare('UPDATE productos SET nombre = :nombre, categoria = :categoria,
            precio = :precio, stock = :stock, socio_id = :socio_id WHERE codigo = :codigo');
        $consulta->execute([
            'codigo' => $codigo, 'nombre' => $d['nombre'], 'categoria' => $d['categoria'],
            'precio' => $d['precio'], 'stock' => $d['stock'], 'socio_id' => $d['socio_id'],
        ]);
        // TODO 4: consulta UPDATE preparada que cambie nombre, categoria, precio, stock y socio_id
        //         WHERE codigo = :codigo (¡sin WHERE se modificarían TODOS los productos!).
    }

    public function eliminar(string $codigo): void
    {
        $consulta = $this->bd->prepare('DELETE FROM productos WHERE codigo = ?');
        $consulta->execute([$codigo]);
        // TODO 5: si rowCount() es 0 no se borró nada: lanza NoEncontrado("No existe el producto $codigo.")
        if ($consulta->rowCount() === 0) {
            throw new NoEncontrado("No existe el producto $codigo.");
        }
    }
}
