# Laboratorio 1 — Hoja de trabajo: del catálogo estático al catálogo generado con PHP

Nombre: ______________________   Fecha: 05/10/2026

## Parte A — Tu plataforma PHP

1. `php -v` → versión: **PHP 8.5.8**, superior a la versión mínima 8.3.
2. El catálogo estático conserva precios antiguos, solo muestra tres productos y dice que fue actualizado en marzo. Cada cambio obliga a editar manualmente el HTML y puede dejar información desactualizada.
3. `php -r 'echo 2 + 3, PHP_EOL;'` imprime `5`. `php public/index.php` ejecuta PHP sin navegador e imprime en la terminal el HTML completo que genera el catálogo.

## Parte B — Completa `public/index.php` (6 TODO)

Se completaron los seis puntos: carga de datos con `require`, escape con `htmlspecialchars`, formato monetario con `number_format`, conteo de disponibles, tratamiento de agotados y pie con fecha, hora y versión de PHP. El resultado del verificador es **12 de 12**.

## Parte C — Cliente frente a servidor

1. En "Ver código fuente" no aparece código PHP. PHP se ejecuta en el servidor y el navegador recibe únicamente el HTML resultante.
2. La cabecera que delata la generación con PHP es `X-Powered-By: PHP/8.5.8`.

| | JavaScript en el navegador (lado del cliente) | PHP (lado del servidor) |
|---|---|---|
| ¿Dónde se ejecuta? | En el navegador del usuario. | En el servidor antes de enviar la respuesta. |
| ¿Quién ve el código? | El usuario puede verlo y descargarlo desde el navegador. | El usuario no ve el código PHP; solo recibe su resultado. |
| ¿Puede leer la base de datos directamente? | No debería conectarse directamente porque expondría credenciales y datos. | Sí, usando credenciales guardadas de manera segura en el servidor. |
| Ejemplo de uso en el catálogo | Filtrar productos o responder a un clic sin recargar toda la página. | Leer productos, calcular disponibles y generar la tabla HTML. |

## Parte D — La trampa del escape

1. Al quitar `e()`, el navegador interpreta `<b>pura</b>` como una etiqueta HTML y muestra la palabra **pura** en negrita, en lugar de mostrar el texto tal como está almacenado.
2. Una persona malintencionada podría introducir un `<script>` para ejecutar JavaScript en el navegador de otros usuarios. Este ataque se conoce como XSS.
3. El comando imprime:

```text
Cera &lt;b&gt;pura&lt;/b&gt; &amp; &quot;miel&quot;
```

## Parte E — Datos fuera de la carpeta pública

1. `datos.php` está fuera de `public/` para que no pueda solicitarse directamente mediante una URL. El servidor puede incluirlo con `require`, pero su contenido interno no se publica.
2. Al pedir `/datos.php`, el servidor integrado responde con estado `200` y muestra el catálogo generado por `public/index.php`; no entrega el archivo real ubicado fuera del directorio público. Esto ocurre porque, al no existir esa ruta dentro de `public/`, el servidor integrado utiliza el archivo índice de la aplicación. El arreglo y su código PHP nunca quedan expuestos.

## Reflexión

Cuando el servidor genera la página, los productos, precios, existencias y totales provienen de una sola fuente de datos. Así se evita repetir información y editar muchas filas de HTML manualmente. También se pueden aplicar reglas de formato y seguridad de manera uniforme, ocultar los archivos internos y cambiar posteriormente el origen de los datos —por ejemplo, de un arreglo a JSON o MySQL— sin rehacer la presentación completa.
