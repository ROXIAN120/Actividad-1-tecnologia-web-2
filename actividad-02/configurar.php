<?php
declare(strict_types=1);
// Genera secretos locales. Nunca imprime ni sobrescribe credenciales existentes.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$archivo = __DIR__ . '/.env';
if (is_file($archivo)) { exit("Configuración local existente; no se modifica.\n"); }
$texto = "BD_HOST=127.0.0.1\nBD_PUERTO=3309\nBD_NOMBRE=actividad02\nBD_USUARIO=actividad02\n"
    . 'BD_CLAVE=' . bin2hex(random_bytes(24)) . "\nBD_ROOT_CLAVE=" . bin2hex(random_bytes(24)) . "\n";
if (file_put_contents($archivo, $texto, LOCK_EX) === false) { throw new RuntimeException('No se pudo crear .env.'); }
echo "Configuración local creada. No compartir .env.\n";
