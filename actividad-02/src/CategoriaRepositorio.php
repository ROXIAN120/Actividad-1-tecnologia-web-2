<?php
declare(strict_types=1);
namespace App;
final class CategoriaRepositorio extends Repositorio
{
    public function todos(): array { return $this->consultar('SELECT id, nombre, descripcion FROM categorias ORDER BY nombre')->fetchAll(); }
    public function existe(int $id): bool { return $this->consultar('SELECT 1 FROM categorias WHERE id = ?', [$id])->fetchColumn() !== false; }
}
