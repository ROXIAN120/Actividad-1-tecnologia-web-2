<?php
declare(strict_types=1);
namespace App;
use RuntimeException;
final class ErrorHttp extends RuntimeException
{
    public function __construct(public readonly int $estado, string $mensaje, public readonly array $errores = [], ?\Throwable $previous = null)
    { parent::__construct($mensaje, 0, $previous); }
}
