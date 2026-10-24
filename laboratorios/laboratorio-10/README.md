# Laboratorio 10 - Seguridad, sesiones y autorización

Este laboratorio amplía el catálogo de Apicultores Flor de la Chiquitanía con autenticación y autorización del lado del servidor.

## Controles implementados

- Contraseñas almacenadas con `password_hash` y verificadas con `password_verify`.
- Actualización de hashes que requieren refuerzo mediante `password_needs_rehash`.
- Sesión con cookie `HttpOnly`, `SameSite=Lax` y regeneración de identificador al iniciar sesión.
- Token CSRF obligatorio en operaciones `POST`.
- Cabeceras CSP, `nosniff` y `Referrer-Policy`.
- Matriz de permisos para Administrador, Operador y Socio.
- Bloqueo temporal después de cinco credenciales fallidas.

## Ejecución

```powershell
docker compose up -d
composer dump-autoload
php verificar.php
php -S 127.0.0.1:8010 -t public public/index.php
```

Abra `http://127.0.0.1:8010`. Espere a que MySQL muestre estado `healthy` antes de ejecutar el verificador.

## Resultado

El verificador finaliza con **15 de 15 comprobaciones aprobadas**. Las capturas de esta ejecución se encuentran en `evidencias/`.
