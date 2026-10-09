# Bitácora de uso de inteligencia artificial

## Actividad 1 — Análisis de la guía

- **Qué pedí:** analizar la guía completa, la presentación y el paquete inicial del Laboratorio 2 para identificar las funciones, pruebas, preguntas y retos.
- **Qué acepté:** la separación entre seis funciones obligatorias, tres funciones de ejemplo, doce comprobaciones oficiales y tres retos para el nivel Estratégico.
- **Qué verifiqué:** ejecuté la versión inicial y confirmé que obtenía 4 de 12, tal como indica la guía.
- **Por qué:** necesitaba comprobar que el material recibido coincidía con la consigna antes de desarrollar.

## Actividad 2 — Funciones obligatorias

- **Qué pedí:** completar y explicar las funciones de descuentos, clasificación de stock, filtrado, valor del inventario, creación de slugs y validación de códigos.
- **Qué acepté:** `match (true)` para reglas por rangos, funciones de arreglos para filtrar y sumar, funciones multibyte para texto UTF-8 y una expresión regular con anclas para los códigos.
- **Qué corregí o verifiqué:** se probaron mayúsculas y minúsculas, índices reordenados, límites de stock, cantidades 5/6/12, tildes, ñ, símbolos y códigos inválidos.
- **Por qué:** cada implementación sigue la técnica solicitada expresamente por la guía y cubre sus casos límite.

## Actividad 3 — Hoja de trabajo y tipos

- **Qué pedí:** ejecutar las trampas del lenguaje y explicar sus resultados en PHP 8.5.8.
- **Qué acepté:** explicar la representación binaria de decimales, la diferencia entre `==` y `===`, el procesamiento multibyte, `switch` frente a `match` y el efecto de `strict_types` en el archivo que llama a la función.
- **Qué verifiqué:** `pruebas.php` demuestra el `TypeError` con tipos estrictos y `pruebas_sin_strict.php` demuestra la conversión de `'5'` al entero `5`.
- **Por qué:** la rúbrica evalúa tanto la ejecución como la explicación del comportamiento.

## Actividad 4 — Retos y evidencias

- **Qué pedí:** resolver también los tres retos sin romper las doce pruebas oficiales.
- **Qué acepté:** argumento nombrado `esSocio`, agrupación de stock por socio y cálculo interno en centavos enteros.
- **Qué corregí o verifiqué:** `php verificar.php` alcanza 12 de 12 y `php verificar_retos.php` alcanza 3 de 3; el reporte fue probado sin filtro, con categoría, con orden descendente y con una entrada HTML potencialmente peligrosa.
- **Por qué:** los retos son necesarios para aspirar al nivel Estratégico y deben coexistir con los requisitos obligatorios.

## Declaración

La inteligencia artificial se utilizó para analizar la consigna, preparar la implementación, explicar los resultados, proponer casos límite y generar evidencias. Antes de presentar el trabajo debo revisar cada función y asegurarme de poder explicar por qué produce su resultado.
