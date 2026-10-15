# Bitácora de uso de inteligencia artificial

## Consulta 1 — Validación de campos

- **Qué pedí:** ayuda para revisar casos límite del código, categoría, precio, stock y texto UTF-8.
- **Qué me resultó útil:** comprobar el formato con una expresión regular, aceptar coma decimal y usar comparaciones estrictas para la categoría y los códigos existentes.
- **Qué verifiqué:** envié los seis valores inválidos indicados por la guía y confirmé una respuesta 422 con seis errores asociados a sus campos.

## Consulta 2 — Respuestas HTTP y PRG

- **Qué pedí:** una explicación de la diferencia entre 422 y 303 y del problema que evita PRG.
- **Qué corregí:** la redirección se realiza únicamente después de guardar correctamente; ante errores se conserva el formulario y se responde 422.
- **Qué comprobé:** el POST válido redirige a `/?creado=POL-100` y F5 ya no vuelve a enviar el registro.

## Consulta 3 — Persistencia en JSON

- **Qué pedí:** revisar para qué sirven las banderas de `json_encode` y `LOCK_EX`.
- **Qué aprendí:** `JSON_UNESCAPED_UNICODE` mantiene legibles las tildes, `JSON_PRESERVE_ZERO_FRACTION` conserva decimales y `LOCK_EX` impide escrituras simultáneas sobre el archivo.
- **Qué verifiqué:** abrí el JSON generado y ejecuté el verificador oficial.

## Resultado y reflexión personal

El apoyo de IA se utilizó para interpretar las reglas y proponer pruebas, y también ayudó a preparar la implementación. Verifiqué el resultado ejecutándolo con PHP y revisando cada comportamiento exigido. El verificador terminó con **13 de 13 comprobaciones**. Antes de presentar debo poder explicar por qué se valida nuevamente en el servidor, cómo funciona PRG y qué limitación de concurrencia conserva el archivo JSON.
