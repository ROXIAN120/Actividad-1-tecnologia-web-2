# Laboratorio 3 — Hoja de trabajo: registrar productos con un formulario seguro

Nombre: Rodrigo Ledezma Sanchez   Fecha: 12/10/2026

Servidor: `php -S 127.0.0.1:8000 -t public` · Verificador: `php verificar.php` (restaura los datos al empezar).

## Parte A — GET y POST

1. Al buscar "cera", la URL quedó como `http://127.0.0.1:8000/?q=cera`. Usar GET deja la búsqueda visible, permite compartirla, guardarla como favorito y volver a ella sin reenviar datos.
2. El formulario vacío usa POST. Los campos viajan en el cuerpo de la petición y no aparecen en la URL.
3. Tabla:

| Acción | Método | ¿Por qué? |
|---|---|---|
| Buscar productos | GET | Es una consulta que no modifica datos y conviene que quede en la URL. |
| Registrar un producto | POST | Cambia el estado del sistema y envía los datos en el cuerpo. |
| Ver la página 2 del catálogo | GET | Es una consulta navegable y compartible, por ejemplo `?pagina=2`. |
| Borrar un producto (clase 4) | POST | Es una acción destructiva; no debe ejecutarse al abrir o precargar un enlace. |

## Parte B — Validación en el servidor

1. Con código `miel`, nombre `ab`, categoría inventada, precio `-5`, stock `2.5` y socio vacío, el servidor responde **422 Unprocessable Content** y muestra **6 errores**, uno por cada campo.
2. La validación del navegador solo ayuda al usuario y puede omitirse o alterarse con DevTools, una petición manual o un cliente HTTP. El servidor es la frontera que realmente protege los datos.
3. El precio `45,50` se normaliza y se guarda en JSON como `45.5`.

## Parte C — Patrón PRG

1. Sin PRG, al presionar F5 el navegador pregunta si se desea reenviar el formulario. Si se acepta, puede intentar registrar el mismo producto otra vez.
2. Con PRG, el POST válido devuelve una redirección y el navegador termina en el catálogo mediante GET. Al presionar F5 solo se repite el GET y no se duplica el registro.
3. La respuesta al POST válido tiene estado **303 See Other** y la cabecera `Location: /?creado=POL-100`.

## Parte D — include y require

1. `index.php` y `nuevo.php` cargan `plantillas/cabecera.php` y `plantillas/pie.php`; además requieren los archivos de lógica que necesitan. Si `cabecera.php` falta, `require` produce un error fatal y detiene la página. `include` genera una advertencia e intenta continuar, posiblemente dejando HTML incompleto.
2. `require_once` carga un archivo obligatorio una sola vez. Evita redeclarar funciones, clases o constantes cuando varias dependencias solicitan el mismo archivo.

## Parte E — El archivo JSON

1. Las tildes se ven directamente, por ejemplo `Élida Chuvé`. `JSON_UNESCAPED_UNICODE` evita convertirlas en secuencias `\uXXXX`.
2. Sin la bandera, `json_encode(45.0)` genera `45`; con `JSON_PRESERVE_ZERO_FRACTION` genera `45.0` y conserva que el valor era decimal.
3. `LOCK_EX` evita que dos escrituras mezclen o trunquen el archivo simultáneamente. Aun así, un JSON no ofrece transacciones completas, consultas, índices, restricciones ni buen control de concurrencia como una base de datos.

## Reflexión

Con tres personas registrando al mismo tiempo podría ocurrir una actualización perdida: dos procesos leen la misma versión y el último en guardar sobrescribe al primero. `LOCK_EX` protege la escritura física, pero no vuelve atómica la secuencia completa de leer, validar y guardar. Una base de datos permitirá transacciones, índices únicos para el código y bloqueos o control de concurrencia para mantener la información consistente.
