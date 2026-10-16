<?php
declare(strict_types=1);
namespace App;
final readonly class Pagina
{
    public function __construct(public array $filas, public int $total, public int $numero, public int $tamano = 10) {}
    public function paginas(): int { return max(1, (int) ceil($this->total / $this->tamano)); }
    public function rango(): string
    {
        if ($this->total === 0) { return 'Sin resultados'; }
        $inicio = ($this->numero - 1) * $this->tamano + 1;
        return 'Mostrando ' . $inicio . '–' . ($inicio + count($this->filas) - 1) . ' de ' . $this->total;
    }
}
