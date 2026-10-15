# Laboratorio 8 — MySQL 8.4 y PDO

Nombre: Rodrigo Ledezma Sanchez · Grupo HTML · Fecha: 14/10/2026

## Parte A — La base de datos

1. `productos` tiene clave primaria `id`, código único `uq_productos_codigo`, `chk_productos_precio` para precio > 0, `chk_productos_stock` para stock >= 0 y `fk_productos_socio` hacia `socios.id` con `ON DELETE RESTRICT`. Los campos obligatorios usan `NOT NULL`; categoría es un `ENUM` cerrado.
2. `DECIMAL(10,2)` almacena valores decimales exactos. `FLOAT` usa representación binaria aproximada; `0.1 + 0.2 == 0.3` produce `false`. PDO devuelve DECIMAL como cadena, por ejemplo `45.00`, conservando su representación.
3. El precio negativo provoca MySQL 3819: `Check constraint 'chk_productos_precio' is violated.` Un socio 999 inexistente, con precio válido, provoca 1452: `Cannot add or update a child row: a foreign key constraint fails`. La base protege las reglas incluso cuando se accede desde otra aplicación o dos solicitudes llegan simultáneamente.

## Parte B — Conexión

1. `mysql:` selecciona el controlador; `host` identifica el servidor, `port` el puerto, `dbname` la base y `charset=utf8mb4` la codificación de la conexión.
2. `ERRMODE_EXCEPTION` convierte fallos SQL en excepciones; `FETCH_ASSOC` devuelve arreglos por nombre de columna; `EMULATE_PREPARES=false` usa preparaciones reales del servidor.
3. Las variables de entorno permiten cambiar credenciales sin editar el código. Los valores de ejemplo del laboratorio son solo de desarrollo; no equivalen a secretos de producción.

## Parte C — Inyección SQL

Las capturas manuales proporcionadas por el usuario documentan las búsquedas de `miel`, `zzz`, `%`, la carga de inyección y la comilla sola tanto en el inicio (8018) como en la solución (8008). La última evidencia, `evidencias/CAPTURA_DESPUES_ZZZ.png`, confirma 0 productos en la solución. Los archivos se identifican en `LEEME_ENTREGA.md`. La captura inicial de la comilla muestra una página 500 con referencia `81220e76`; no muestra el detalle SQL del registro, por lo que el código 1064 se explica a partir de la consulta vulnerable y de la guía, no como un dato visible en esa imagen.

1. `miel` encuentra 3 productos.
2. `zzz` encuentra 0; `zzz' OR 1=1 -- -` devuelve los 6 porque el SQL pasa a contener `WHERE p.nombre LIKE '%zzz' OR 1=1 -- -%' OR p.codigo LIKE ...`. El comentario anula el resto y la condición verdadera cambia la consulta.
3. Una comilla suelta provoca error sintáctico 1064 y respuesta 500; el detalle se registra internamente.
4. `%` devuelve 6 porque se interpreta como comodín de LIKE.
5. Después, `miel` conserva 3; `zzz`, la carga de inyección, la comilla y `%` devuelven 0 sin romper la consulta. `prepare()` separa la estructura SQL de los valores; `addcslashes` escapa `%`, `_` y barra para buscar esos caracteres literalmente.
6. Con preparaciones nativas no se reutiliza el mismo marcador nombrado dentro de una sentencia. `:patron` y `:patron2` son diferentes aunque reciban el mismo valor, evitando HY093.

## Parte D — CRUD

1. El formulario normaliza `pol-100` a `POL-100`; `32,5` se almacena como DECIMAL `32.50`, con socio 3 y stock 15.
2. Dos peticiones podrían superar a la vez la comprobación previa de unicidad. La restricción UNIQUE decide finalmente; el error 1062 se traduce a `Conflicto` 409 y conserva la `PDOException` como causa.
3. La actualización usa `UPDATE productos SET nombre=:nombre, categoria=:categoria, precio=:precio, stock=:stock, socio_id=:socio_id WHERE codigo=:codigo`. Sin WHERE se modificarían todas las filas.
4. Al borrar un código ausente, `rowCount()` devuelve 0 y la aplicación lanza `NoEncontrado`, con respuesta 404.

## Parte E — Verificación

Resultado real de `php verificar.php`: **15 de 15 comprobaciones aprobadas con MySQL 8.4**. La salida completa está en `evidencias/RESULTADO_VERIFICADOR.txt`.

El verificador prueba indisponibilidad de MySQL apuntando a un puerto sin servidor. La respuesta esperada es 500 con referencia, sin usuario, clave, puerto ni SQLSTATE. Así se prueba el control de errores sin detener otras bases de datos.

## Reflexión

Una consulta preparada funciona como un formulario con casillas: la instrucción ya establece qué operación se hará; lo escrito en las casillas aporta valores y nunca se convierte en nuevas órdenes. Escapar los comodines es un control adicional sobre el significado de LIKE, distinto de impedir la inyección.
