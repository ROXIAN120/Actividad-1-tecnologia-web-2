<?php
declare(strict_types=1);

require_once __DIR__ . '/Categoria.php';

/** Entidad del dominio. No conoce HTML ni detalles del archivo JSON. */
final class Producto implements JsonSerializable
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        public readonly Categoria $categoria,
        public readonly float $precio,
        public readonly int $stock,
        public readonly string $socio,
        public readonly ?string $foto = null,
    ) {
    }

    /** @param array<string, mixed> $datos */
    public static function desdeArray(array $datos): self
    {
        return new self(
            codigo: (string) $datos['codigo'],
            nombre: (string) $datos['nombre'],
            categoria: new Categoria((string) $datos['categoria']),
            precio: (float) $datos['precio'],
            stock: (int) $datos['stock'],
            socio: (string) $datos['socio'],
            foto: isset($datos['foto']) && $datos['foto'] !== '' ? (string) $datos['foto'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function aArray(): array
    {
        $datos = [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria->nombre,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'socio' => $this->socio,
        ];
        if ($this->foto !== null) {
            $datos['foto'] = $this->foto;
        }
        return $datos;
    }

    public function nivelStock(): string
    {
        return match (true) {
            $this->stock === 0 => 'Agotado',
            $this->stock <= 5 => 'Bajo',
            default => 'Disponible',
        };
    }

    public function jsonSerialize(): array
    {
        return $this->aArray();
    }
}
