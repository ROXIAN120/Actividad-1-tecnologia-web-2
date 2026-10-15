# Bitácora de uso de inteligencia artificial

## Consulta 1 — Comprender la actividad

- **Qué pedí:** una explicación general de la consigna y de los seis puntos que debía completar en `index.php`.
- **Qué me resultó útil:** identificar que el catálogo debía cargar los datos con `require`, recorrerlos con `foreach` y mostrar seis productos sin exponer el código PHP.
- **Qué revisé:** comparé la explicación con la guía y ejecuté la versión inicial para conocer su estado.
- **Por qué lo usé:** quería ordenar los pasos antes de comenzar y entender qué comprobaba el verificador.

## Consulta 2 — Formato y seguridad

- **Qué pedí:** una explicación de `number_format`, `htmlspecialchars` y del riesgo de XSS.
- **Qué me resultó útil:** usar `number_format` para mostrar precios como `Bs 1.234,50` y escapar cada texto antes de enviarlo al navegador.
- **Qué corregí:** mantuve `e()` en el nombre de la cera para que `<b>pura</b>` se muestre como texto y no como una etiqueta HTML.
- **Por qué lo acepté:** la prueba demuestra que un dato externo nunca debe interpretarse como código.

## Consulta 3 — Comprobación del resultado

- **Qué pedí:** ayuda para interpretar los mensajes del verificador y revisar los casos que faltaban.
- **Qué comprobé:** seis productos, cinco disponibles, precios bolivianos, producto agotado, fecha del servidor, hoja de estilos y datos fuera de `public/`.
- **Resultado:** `php verificar.php` terminó con 12 de 12 comprobaciones.
- **Qué aprendí:** PHP construye el HTML en el servidor y el navegador recibe solamente el resultado.

## Reflexión personal

Usé la inteligencia artificial como apoyo para entender funciones y revisar casos de prueba. No tomé el resultado del verificador como explicación suficiente: también revisé para qué sirve cada función y cómo protege la salida. Antes de presentar el laboratorio debo poder explicar cada línea y justificar por qué los datos permanecen fuera de la carpeta pública.
