# Bitácora de uso de inteligencia artificial

## Consulta 1 — Tipos y comparaciones

- **Qué pedí:** una explicación de los resultados de `0.1 + 0.2`, las diferencias entre `==` y `===`, y el uso de funciones `mb_*`.
- **Qué me resultó útil:** entender que los decimales binarios pueden tener pequeñas diferencias y que `===` evita conversiones automáticas inesperadas.
- **Qué verifiqué:** ejecuté `pruebas.php` con PHP 8.5.8 y comparé la salida con la guía.
- **Por qué lo usé:** necesitaba explicar los resultados con mis propias palabras, no limitarme a copiarlos.

## Consulta 2 — Reglas del inventario

- **Qué pedí:** orientación sobre casos límite para descuentos, niveles de stock, filtros y validación de códigos.
- **Qué me resultó útil:** revisar los límites 5, 6, 12, 0, 20 y 21; probar categorías con distintas mayúsculas; y comprobar códigos válidos e inválidos.
- **Qué corregí:** confirmé que el filtro reindexa el arreglo, que `match` compara estrictamente y que la expresión regular usa anclas al inicio y al final.
- **Por qué lo acepté:** esos casos coinciden con las pruebas exigidas por el laboratorio.

## Consulta 3 — Cadenas y seguridad

- **Qué pedí:** casos de prueba para convertir nombres con tildes, ñ y símbolos en enlaces limpios.
- **Qué comprobé:** `Miel de cusí 350 g` se convierte en `miel-de-cusi-350-g` y el filtro recibido por URL se muestra escapado.
- **Qué aprendí:** las funciones multibyte son necesarias para texto UTF-8 y los datos de la URL deben escaparse al mostrarse.

## Consulta 4 — Verificación y retos

- **Qué pedí:** comparar el cálculo con decimales y con centavos enteros, y revisar los retos de descuento para socios y stock agrupado.
- **Qué comprobé:** el verificador oficial terminó con 12 de 12; además, el descuento de socio, el stock por socio y el cálculo en centavos produjeron los resultados esperados.
- **Por qué lo incorporé:** trabajar con centavos evita errores de redondeo y los retos permiten aplicar las funciones en un caso más cercano al inventario real.

## Reflexión personal

La inteligencia artificial se utilizó como apoyo para aclarar conceptos y proponer casos de prueba. La parte más importante fue ejecutar cada ejemplo en PHP 8.5.8 y revisar los límites, porque una explicación puede parecer correcta y aun así fallar en un caso concreto. Antes de presentar debo poder explicar las seis funciones, la expresión regular y el efecto de `strict_types`.
