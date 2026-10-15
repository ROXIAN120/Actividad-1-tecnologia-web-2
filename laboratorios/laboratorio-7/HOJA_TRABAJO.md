# Laboratorio 7 — Excepciones, front controller y plantillas

Nombre: Rodrigo Ledezma Sanchez · Grupo HTML · Fecha: 14/10/2026

## Parte A — Una sola puerta de entrada

1. Todas las rutas dinámicas son atendidas por `public/index.php`, que configura el router y ejecuta el controlador correspondiente.
2. En el inicio, el CSS llega al router y devuelve 404 porque no existe esa ruta dinámica. Ahora, si el servidor embebido recibe un archivo real dentro de `public`, el script devuelve `false` y PHP sirve el archivo con su tipo correcto.
3. En Nginx: `location / { try_files $uri /index.php$is_args$args; }`.
4. El patrón es `#^/producto/(?P<codigo>[^/]+)$#`. El grupo nombrado captura `MIE-1K` y se envía al controlador como argumento llamado `codigo`.

## Parte B — Excepciones con significado

```text
RuntimeException
└── ErrorAplicacion (abstracta)
    ├── NoEncontrado: 404
    ├── MetodoNoPermitido: 405
    ├── Conflicto: 409
    └── DatosInvalidos: 422
```

2. `Conflicto` corresponde a 409: la cantidad solicitada puede ser válida, pero supera el stock actual.
3. Antes, POST a `/` terminaba en 404 porque el router no registraba los métodos permitidos. Debe devolver 405 con `Allow: GET`.
4. En el código inicial, una venta de 5 CER-1K causaba una página 500 que mostraba `DomainException`, el mensaje de stock y la ruta y línea internas. Los problemas son tratar un conflicto previsto como fallo interno y revelar información técnica. Esta descripción inicial se obtiene de la versión del docente; la comprobación automática se realizó sobre la versión corregida.
5. Pasar `$e` como `previous` conserva la causa original: `Conflicto` representa el error HTTP y `getPrevious()` permite al desarrollador inspeccionar la excepción de dominio.

## Parte C — Escape automático

1. Antes, el texto de búsqueda se insertaba sin escapar en HTML, permitiendo cerrar atributos o insertar etiquetas. La carga exacta puede ser bloqueada por el navegador o quedar dentro de un atributo; la falla es la falta de escape, aunque no aparezca una alerta.
2. `Plantilla::escapar()` aplica `htmlspecialchars` a cada cadena y recorre arreglos. Las plantillas reciben los textos escapados y no deben volver a escaparlos para evitar codificación doble.
3. `Crudo` identifica HTML confiable, como el parcial renderizado que se inserta en la base. Nunca debe envolver directamente texto de usuarios o datos externos.
4. Rechaza objetos para que las vistas reciban datos preparados, sin acceso directo al comportamiento del modelo ni conversiones implícitas inesperadas.

## Parte D — Errores inesperados

1. Con JSON dañado, el manejador inicial incluía clase, mensaje, archivo y línea en la respuesta 500.
2. Se revelaban la estructura de carpetas, nombres de clases y detalles del almacenamiento, útiles para reconocer la aplicación.
3. Ahora el usuario recibe un mensaje genérico con una referencia de ocho caracteres hexadecimales. `datos/errores.log` conserva fecha, referencia, clase, mensaje, archivo y línea para soporte. El verificador provoca un JSON roto y contrasta la referencia de la respuesta con la del registro; después limpia sus archivos temporales.

4. Prueba manual documentada: se inició el servidor en 8017 con `CATALOGO_ARCHIVO` apuntando a `LEEME_ENTREGA.md`, un archivo existente que no es JSON. No se modificó el catálogo real. La página mostró 500 y la referencia `b95e59a5`, sin clase, ruta ni traza. El registro contiene esa misma referencia y `JsonException: Syntax error` en `src/Catalogo.php:21`. Las capturas están en `evidencias/CAPTURA_ERROR_500_REFERENCIA.png` y `evidencias/CAPTURA_REGISTRO_ERROR_500.png`. La captura `CAPTURA_PRUEBA_500_Y_LIMPIEZA.png` documenta el comando utilizado y la eliminación posterior de la variable de entorno.

## Parte E — Verificación

`php verificar.php`: **15 de 15**. Incluye rutas, CSS, API, escape, cotización Bs 636,00, errores 404/405/409/422 y manejo seguro de 500.

## Reflexión

Pedir más stock del disponible es un problema previsto: el usuario debe recibir un 409 claro para corregir su pedido. Un JSON corrupto es inesperado: el usuario recibe un 500 y una referencia, mientras soporte o desarrollo consulta el registro interno. Las rutas y credenciales nunca se incluyen en el mensaje público.
