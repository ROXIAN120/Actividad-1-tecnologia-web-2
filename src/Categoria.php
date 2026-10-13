<?php
declare(strict_types=1);

/** Categoría válida del catálogo. Centraliza las opciones permitidas. */
final class Categoria
{
    private const PERMITIDAS = ['Miel', 'Derivados', 'Cera'];

    public function __construct(public readonly string $nombre)
    {
        if (!self::esValida($nombre)) {
            throw new InvalidArgumentException("Categoría no permitida: $nombre");
        }
    }

    /** @return list<string> */
    public static function nombres(): array
    {
        return self::PERMITIDAS;
    }

    public static function esValida(string $nombre): bool
    {
        return in_array($nombre, self::PERMITIDAS, true);
    }
}
