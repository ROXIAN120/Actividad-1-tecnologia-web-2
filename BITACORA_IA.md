# Bitácora de uso de inteligencia artificial

## Actividad 1 — Revisión de la consigna

- **Qué pedí:** analizar los documentos de la clase, el laboratorio, la guía de instalación y los archivos iniciales para determinar la tarea.
- **Qué acepté:** la identificación de los seis TODO de `public/index.php`, los cuatro entregables y la meta de 12 de 12 comprobaciones.
- **Qué corregí o verifiqué:** confirmé directamente que PHP 8.5.8 está instalado y detecté que faltaba `Verificador.php` en el paquete recibido.
- **Por qué:** necesitaba distinguir los requisitos de entrega de las explicaciones teóricas y comprobar si el laboratorio podía ejecutarse.

## Actividad 2 — Desarrollo y comprobación

- **Qué pedí:** preparar una copia resuelta del laboratorio dentro de una carpeta independiente llamada `tarea`.
- **Qué acepté:** usar `require` para cargar los datos, `htmlspecialchars` para escapar las salidas, `number_format` para los precios, un recorrido para contar disponibles y una condición para marcar el producto agotado.
- **Qué corregí o verifiqué:** se añadió localmente la dependencia faltante del verificador, se comprobó la sintaxis de PHP y se ejecutaron las 12 pruebas automáticas.
- **Por qué:** estas decisiones corresponden a los conceptos de la clase y permiten demostrar que el resultado se genera en el servidor, presenta los datos correctamente y evita que un dato se interprete como HTML o JavaScript.

## Actividad 3 — Evidencias y reflexión

- **Qué pedí:** generar evidencia del catálogo y del resultado del verificador, y redactar respuestas comprensibles para la hoja de trabajo.
- **Qué acepté:** las evidencias se obtuvieron ejecutando la solución real; las respuestas explican la diferencia entre cliente y servidor, el riesgo de XSS y la razón de mantener `datos.php` fuera de `public/`.
- **Qué corregí o verifiqué:** revisé que la captura muestre los seis productos, cinco disponibles, el producto agotado, los precios bolivianos y el texto `<b>` escapado.
- **Por qué:** la entrega requiere demostrar funcionamiento y poder explicar cada decisión, no solamente presentar código.

## Declaración

La inteligencia artificial se utilizó para analizar la consigna, proponer y preparar la implementación, recuperar la herramienta de verificación faltante, redactar explicaciones y generar evidencias. Antes de presentar el trabajo, debo revisar el código y asegurarme de poder explicar cada línea y cada decisión con mis propias palabras.
