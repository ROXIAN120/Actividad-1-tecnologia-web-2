<?php
declare(strict_types=1);
namespace App;
use PDO;
final class Conexion
{
    public static function crear(): PDO
    {
        $c = require __DIR__ . '/../config/bd.php';
        return new PDO("mysql:host={$c['host']};port={$c['puerto']};dbname={$c['base']};charset=utf8mb4", $c['usuario'], $c['clave'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        ]);
    }
}
