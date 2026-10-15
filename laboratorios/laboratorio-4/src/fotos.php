<?php
// src/fotos.php — Subida SEGURA de fotos de productos.
// Regla: nada de lo que dice el navegador sobre el archivo (nombre, tipo) es confiable.
declare(strict_types=1);

const CARPETA_FOTOS = __DIR__ . '/../datos/fotos';       // FUERA de public/: no se puede ejecutar ni listar
const TAM_MAXIMO = 2 * 1024 * 1024;                       // 2 MB
const TIPOS_PERMITIDOS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

/**
 * Valida y guarda la foto subida en el campo $campo de $_FILES.
 * @return array{nombre: ?string, error: ?string}  nombre del archivo guardado o mensaje de error (null si no se subió nada)
 */
function guardar_foto(array $archivos, string $campo = 'foto'): array
{
    $f = $archivos[$campo] ?? null;
    if ($f === null || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['nombre' => null, 'error' => null];                     // la foto es opcional
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $motivo = match ($f['error']) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'La foto supera el tamaño permitido.',
            UPLOAD_ERR_PARTIAL => 'La foto llegó incompleta; vuelve a intentarlo.',
            default => 'No se pudo recibir la foto.',
        };
        return ['nombre' => null, 'error' => $motivo];
    }
    if ($f['size'] > TAM_MAXIMO) {
        return ['nombre' => null, 'error' => 'La foto supera los 2 MB.'];
    }
    // Se inspecciona el contenido; el tipo y el nombre enviados por el navegador no son confiables.
    $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!is_string($tipo) || !isset(TIPOS_PERMITIDOS[$tipo]) || getimagesize($f['tmp_name']) === false) {
        return ['nombre' => null, 'error' => 'El archivo no es una imagen JPG, PNG o WebP.'];
    }
    // Nombre nuevo y aleatorio: el nombre original podría ser "../../index.php" o repetirse
    $nombre = bin2hex(random_bytes(12)) . '.' . TIPOS_PERMITIDOS[$tipo];
    if (!move_uploaded_file($f['tmp_name'], CARPETA_FOTOS . '/' . $nombre)) {
        return ['nombre' => null, 'error' => 'No se pudo guardar la foto.'];
    }
    return ['nombre' => $nombre, 'error' => null];
}

/** Ruta segura de una foto guardada, o null si el nombre no tiene el formato esperado (evita ../). */
function ruta_foto(string $nombre): ?string
{
    if (preg_match('/^[a-f0-9]{24}\.(jpg|png|webp)$/', $nombre) !== 1) {
        return null;
    }
    $ruta = CARPETA_FOTOS . '/' . $nombre;
    return is_file($ruta) ? $ruta : null;
}

function borrar_foto(?string $nombre): void
{
    if ($nombre !== null && ($ruta = ruta_foto($nombre)) !== null) {
        unlink($ruta);
    }
}
