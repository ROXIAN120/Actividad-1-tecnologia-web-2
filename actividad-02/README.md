# Actividad 02: De JSON a MySQL

Entrega final independiente de los laboratorios originales. Autor: Rodrigo Ledezma Sanchez, grupo HTML. Tecnología Web II, UPDS. Fecha: 16 de octubre de 2026.

## Ejecutar en PowerShell

Requisitos: Docker Desktop iniciado, PHP >= 8.3 con pdo_mysql, mbstring y curl, y Composer. Desde esta carpeta:

```powershell
php configurar.php
docker compose up -d --wait mysql
composer dump-autoload
php pruebas/verificar.php
php pruebas/medir.php
php importar_json.php
php -S 127.0.0.1:8020 -t public public/index.php
```

Abrir http://127.0.0.1:8020/productos y http://127.0.0.1:8020/socios. Mantener la terminal del servidor abierta; Ctrl+C lo detiene. La base usa el puerto 3309 y el proyecto Docker tw2-actividad02-final, sin modificar los volúmenes de las tareas anteriores. `php configurar.php` crea una sola vez el .env privado con claves aleatorias; no las muestra ni sobrescribe un archivo existente. `.env.example` solo contiene marcadores, no contraseñas utilizables. No subir .env, vendor ni registros privados a GitHub.

Los scripts SQL se ejecutan automáticamente al inicializar el volumen vacío de esta aplicación. No usar `docker compose down -v` para solucionar errores: eliminaría sus datos. Si ya existe un volumen, conservar el mismo .env; cambiar la contraseña en el archivo no modifica usuarios existentes de MySQL. Para detener sin borrar: `docker compose stop`.

## Funciones y evidencia

- CRUD de productos y socios, consultas preparadas nativas, validación, FK y CHECK.
- Categorías normalizadas: tres categorías, ocho socios y 36 productos de ejemplo.
- Paginación numerada de ambas entidades; modo por clave ID para productos, sin OFFSET ni COUNT.
- Escape HTML, CSP, token CSRF, métodos HTTP controlados y mensajes privados para errores internos.
- 48 pruebas con fixtures propios y limpieza selectiva. Las pruebas esperan los datos iniciales; no restauran tablas ni eliminan datos del usuario.
- EXPLAIN y EXPLAIN ANALYZE medidos sobre 36 productos: ver evidencias/EXPLAIN_MEDICIONES.json. No se afirma una mejora general de latencia.
- Capturas auténticas de navegación en evidencias/*.jpg. Los registros de demostración fueron eliminados al finalizar.

## Migración JSON

`migracion/origen_actividad01.json` conserva los seis productos originales. `php importar_json.php` simula y revierte; para confirmar, usar `php importar_json.php --aplicar`. Se aceptan archivos alternativos como primer argumento. Los códigos existentes se omiten sin sobrescribirlos. La semilla SQL ya incluye los seis productos: la ejecución documentada omitió seis, no insertó seis nuevos. Las pruebas sí comprobaron inserción, reversión e idempotencia con un producto temporal. Si falta comunidad en el JSON original, un socio nuevo se registra como “Sin especificar”, sin inventar datos.

## Organización

`public/`: único directorio público; `config/`: carga de configuración; `src/`: conexión, repositorios, validación e importación; `plantillas/`: vistas escapadas; `sql/`: esquema y semilla; `pruebas/`: verificación y mediciones; `evidencias/`: resultados y capturas; `documentos/`: informe final y presentación. La guía de defensa está en GUIA_DEFENSA.md.

Esta aplicación es educativa y local, sin autenticación ni autorización por roles. No debe exponerse a Internet como sistema productivo. Los laboratorios históricos se conservan como ejercicios; sus ejemplos educativos no son las credenciales aleatorias de esta entrega. No se afirma que todo el historial Git esté libre de ejemplos de claves.

Repositorio: https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/main/actividad-02

La defensa oral y el envío al aula virtual deben realizarlos los participantes. Los documentos no certifican calificación ni recepción en la plataforma.
