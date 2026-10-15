# Laboratorio 8 — Ejecución y entrega

Rodrigo Ledezma Sanchez · Grupo HTML

Requiere Docker Desktop encendido, PHP >=8.3 con pdo_mysql y Composer 2. La configuración de esta carpeta usa MySQL 8.4 en 127.0.0.1:3307 y PHP nativo desde PowerShell; no requiere construir una imagen PHP externa.

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 8"
docker compose up -d mysql
docker compose ps
composer dump-autoload
php verificar.php
php -S 127.0.0.1:8008 -t public public/index.php
```

Esperar a que MySQL esté `healthy` antes de ejecutar el verificador. Abrir <http://127.0.0.1:8008/productos> y `/productos/nuevo`.

La base y su volumen son exclusivos de esta tarea. El verificador restaura las tablas de ejemplo al empezar y terminar: ejecutarlo antes de registrar datos propios. No ejecutar los SQL de restauración contra una base con información real.

El archivo `docker-compose.original.yml` conserva la configuración alternativa del docente; la utilizada es `docker-compose.yml`.

Entregar proyecto sin `vendor/`, hoja y bitácora, captura del verificador 15/15 y capturas de búsquedas antes/después. La versión final ya está corregida; para evidencia del estado vulnerable utilizar el inicio del ZIP solo en el laboratorio local. Los retos opcionales de auditoría, permisos mínimos y listado de socios no están implementados.

Evidencias recibidas: `evidencias/CAPTURA_MYSQL_HEALTHY.png`, `evidencias/CAPTURA_VERIFICADOR.png` (15/15), `evidencias/CAPTURA_CATALOGO.png` (6 productos), las diez capturas antes/después detalladas abajo y `evidencias/CAPTURA_BUSQUEDA_SIN_RESULTADOS.png` (texto qww2w, 0 productos). La captura de un intento con dos comandos concatenados no se incluye como evidencia de ejecución correcta.

Comparación antes/después con capturas reales del usuario:

| Búsqueda | Inicio vulnerable (8018) | Solución (8008) |
| --- | --- | --- |
| `miel` | 3 productos — `evidencias/CAPTURA_ANTES_MIEL.png` | 3 productos — `evidencias/CAPTURA_DESPUES_MIEL.png` |
| `%` | 6 productos — `evidencias/CAPTURA_ANTES_PORCENTAJE.png` | 0 productos — `evidencias/CAPTURA_DESPUES_PORCENTAJE.png` |
| `zzz' OR 1=1 -- -` | 6 productos — `evidencias/CAPTURA_ANTES_INYECCION_SQL.png` | 0 productos — `evidencias/CAPTURA_DESPUES_INYECCION_SQL.png` |
| `zzz` | 0 productos — `evidencias/CAPTURA_ANTES_ZZZ.png` | 0 productos — `evidencias/CAPTURA_DESPUES_ZZZ.png` |
| `'` | Página 500, referencia `81220e76` — `evidencias/CAPTURA_ANTES_COMILLA.png` | 0 productos sin error visible — `evidencias/CAPTURA_DESPUES_COMILLA.png` |

Quedan completas las diez capturas de los cinco casos antes/después de esta comparación. El comodín `%` y la alteración de la consulta por inyección son problemas distintos: el primero se corrige escapando comodines de LIKE, y el segundo separando SQL y datos mediante consultas preparadas. Detener el servidor vulnerable del puerto 8018 con Ctrl+C al finalizar; no publicarlo.

Para detener esta base: `docker compose stop mysql`. Para volver a iniciarla: `docker compose start mysql`.
