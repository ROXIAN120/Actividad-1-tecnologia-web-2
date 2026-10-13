# Bitácora de uso de inteligencia artificial

## Consulta 1 — Validación real de imágenes

- **Qué pedí:** revisar riesgos de confiar en el nombre, la extensión y `$_FILES['type']`.
- **Qué me resultó útil:** inspeccionar el contenido con `finfo`, confirmar que sea una imagen con `getimagesize` y aceptar solamente JPEG, PNG o WebP.
- **Qué verifiqué:** un archivo PHP llamado `foto.jpg` fue rechazado con 422 y una imagen PNG real fue aceptada.

## Consulta 2 — Nombres y rutas seguras

- **Qué pedí:** casos de ataque con nombres repetidos y recorridos `../`.
- **Qué corregí:** el servidor genera nombres aleatorios y `ruta_foto` acepta únicamente el formato que la aplicación produce.
- **Qué comprobé:** intentar leer `../productos.json` devuelve 404 y la foto se entrega con su MIME real y `nosniff`.

## Consulta 3 — Editar y eliminar

- **Qué pedí:** revisar qué archivos podrían quedar huérfanos y por qué no se debe borrar mediante GET.
- **Qué aprendí:** una foto recién subida debe eliminarse si falla otro campo; al reemplazarla se borra la anterior; y una acción destructiva debe exigir POST.
- **Qué verifiqué:** cambiar una foto deja un único archivo, editar sin foto conserva la existente y eliminar borra el producto junto con su imagen.

## Resultado y reflexión personal

La IA se utilizó como apoyo para analizar riesgos, preparar la implementación y plantear pruebas. No tomé el resultado como prueba suficiente: ejecuté el verificador oficial y revisé las respuestas HTTP y los archivos creados. El resultado fue **13 de 13 comprobaciones**. Antes de presentar debo saber explicar por qué el MIME declarado por el navegador no es confiable, por qué las fotos están fuera de `public` y cómo se evitan archivos huérfanos.

## Consulta 4 — Integración orientada a objetos

- **Qué pedí:** revisar cómo integrar los cuatro laboratorios usando `Producto`, `Categoria` y `Catalogo` sin mezclar persistencia con las vistas.
- **Qué incorporé:** promoción de propiedades, propiedades de solo lectura, parámetros nombrados, `match`, funciones flecha y el operador nullsafe.
- **Qué verifiqué:** además de las 13 pruebas funcionales, ejecuté `verificar_poo.php`; las 10 comprobaciones de clases, encapsulamiento y persistencia finalizaron correctamente.
- **Qué debo poder explicar:** `Producto` representa una entidad, `Categoria` protege las opciones válidas y `Catalogo` es responsable de transformar objetos a JSON y recuperarlos.

## Integrante

- Rodrigo Ledezma Sanchez

## Grupo

- HTML
