# Laboratorio 4 — Hoja de trabajo: fotos de productos, editar y eliminar

Nombre: Rodrigo Ledezma Sanchez   Fecha: 12/10/2026

Servidor: `php -S 127.0.0.1:8000 -t public` · Verificador: `php verificar.php` (restaura datos y fotos).

## Parte A — Cómo llega un archivo

1. `$_FILES['foto']` contiene: `name`, nombre indicado por el cliente; `type`, tipo declarado por el cliente y no confiable; `tmp_name`, archivo temporal creado por PHP; `error`, código de recepción; y `size`, cantidad de bytes recibidos.
2. Sin `enctype="multipart/form-data"`, el navegador no envía el archivo y PHP lo informa como ausente.
3. En el PHP utilizado, `upload_max_filesize` es **2M** y `post_max_size` es **8M**. La aplicación además limita cada foto a 2 MB.

## Parte B — No confiar en el navegador

1. Antes de completar la validación, el archivo falso podía aceptarse usando su nombre `foto.jpg`, aunque el cliente declarara `image/jpeg`.
2. `finfo` inspecciona el contenido y detecta `text/x-php`, no una imagen JPEG real.
3. Después de completar la validación, el archivo se rechaza con estado 422. El nombre original es peligroso porque puede contener rutas, extensiones ejecutables, caracteres problemáticos o coincidir con otro archivo. Por eso se genera un nombre aleatorio de 24 caracteres hexadecimales y la extensión se obtiene del MIME real.

## Parte C — Dónde viven las fotos

1. Se guardan en `datos/fotos`, fuera de `public`, para que el servidor no pueda ejecutarlas ni listarlas directamente. Si un archivo PHP llegara a una carpeta pública y el servidor lo interpretara, podría ejecutar código del atacante.
2. `foto.php?f=../productos.json` responde **404 Foto no encontrada** porque `ruta_foto()` solo acepta nombres con el patrón de 24 caracteres hexadecimales y extensión `jpg`, `png` o `webp`.
3. `X-Content-Type-Options: nosniff` impide que el navegador intente adivinar otro tipo de contenido distinto del `Content-Type` enviado.

## Parte D — Editar y eliminar

1. Antes del control, una petición GET podía llegar a ejecutar la eliminación. Después, GET responde **405 Method Not Allowed**, incluye `Allow: POST` y el producto permanece intacto.
2. Antes de borrar la foto reemplazada, el archivo anterior quedaba huérfano en `datos/fotos`. Ahora una foto nueva se guarda y la anterior se elimina.
3. Si otro campo falla, la foto nueva se borra inmediatamente. Esto evita archivos huérfanos asociados a productos que nunca fueron guardados.

## Parte E — Verificación

`php verificar.php` produjo **13 de 13 comprobaciones correctas**.

## Reflexión

| Ataque o problema | Línea o control que lo evita |
|---|---|
| PHP disfrazado con extensión `.jpg` | `finfo(FILEINFO_MIME_TYPE)`, lista `TIPOS_PERMITIDOS` y `getimagesize()`. |
| Recorrido de directorios como `../productos.json` | Expresión regular estricta en `ruta_foto()`. |
| Sobrescritura o nombre malicioso enviado por el cliente | `bin2hex(random_bytes(12))` crea un nombre nuevo. |
| Ejecución directa de un archivo subido | Las fotos permanecen fuera de `public` y se entregan mediante `foto.php`. |
| Borrado mediante un enlace GET | Comprobación de `REQUEST_METHOD`, estado 405 y cabecera `Allow: POST`. |
