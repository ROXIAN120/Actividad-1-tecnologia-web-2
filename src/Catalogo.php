<?php
declare(strict_types=1);

require_once __DIR__ . '/Producto.php';

/** Colección de productos y única responsable de la persistencia JSON. */
final class Catalogo
{
    public function __construct(private readonly string $archivo)
    {
    }

    /** @return list<Producto> */
    public function todos(): array
    {
        if (!is_file($this->archivo)) {
            return [];
        }
        $filas = json_decode((string) file_get_contents($this->archivo), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($filas)) {
            return [];
        }
        return array_map(fn(array $fila): Producto => Producto::desdeArray($fila), $filas);
    }

    public function buscarPorCodigo(string $codigo): ?Producto
    {
        foreach ($this->todos() as $producto) {
            if ($producto->codigo === $codigo) {
                return $producto;
            }
        }
        return null;
    }

    /** Ejemplo de operador nullsafe: evita acceder a una propiedad cuando no hay resultado. */
    public function nombrePorCodigo(string $codigo): ?string
    {
        return $this->buscarPorCodigo($codigo)?->nombre;
    }

    /** @param list<Producto> $productos */
    public function guardar(array $productos): void
    {
        $json = json_encode(
            $productos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR
        );
        if (file_put_contents($this->archivo, $json . PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException('No se pudo guardar el catálogo.');
        }
    }
}
