<?php
declare(strict_types=1);
$local = is_file(__DIR__ . '/../.env') ? parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW) : [];
$valor = static function (string $clave) use ($local): string {
    $v = getenv($clave);
    $v = $v !== false ? $v : ($local[$clave] ?? '');
    if (!is_string($v) || $v === '' || $v === 'GENERAR_LOCALMENTE') { throw new RuntimeException('Falta configuración privada.'); }
    return $v;
};
return ['host' => $valor('BD_HOST'), 'puerto' => $valor('BD_PUERTO'), 'base' => $valor('BD_NOMBRE'),
    'usuario' => $valor('BD_USUARIO'), 'clave' => $valor('BD_CLAVE')];
