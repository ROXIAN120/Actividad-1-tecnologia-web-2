# Bitácora de IA — Laboratorio 6

Nombre: Rodrigo Ledezma Sanchez · Grupo: HTML · Fecha: 14/10/2026

## Consulta realizada

Se solicitó al asistente preparar el proyecto del laboratorio 6 siguiendo los archivos oficiales, con documentación y verificaciones.

## Sugerencia recibida e implementación

El asistente completó las descripciones del enum, el descuento mayorista, la serialización con `parent::`, disponibilidad de servicios, filtro de servicios y autoload en la API. Preparó también el diagrama y las respuestas.

## Qué se revisó y cambió

Se verificó que las relaciones del diagrama corresponden al código: `Articulo` implementa `Vendible` y `JsonSerializable`, utiliza `ConBolivianos` y es padre de `Producto` y `Servicio`. Composer genera el autoload local a partir del prefijo PSR-4 existente. No se añadieron paquetes externos.

## Comando de comprobación y resultado real

- Antes de generar el autoload, `php verificar.php` indicó que faltaba `vendor/autoload.php`.
- `composer dump-autoload --no-interaction`: autoload generado correctamente.
- `php demo.php`: pedido mixto 636, inventario 5030 y serialización del servicio completa.
- `php verificar.php`: 14 de 14 comprobaciones, incluyendo la respuesta JSON de la API.

## Reflexión y alcance del apoyo

La IA preparó la implementación y la documentación; no se afirma que el estudiante ya haya realizado una defensa personal de todo el código. La guía prohíbe pedir los TODO resueltos, por lo que este alcance debe declararse. Para presentar hay que comprender la diferencia entre contrato, herencia y reutilización por trait y poder explicar por qué el servicio no recibe el descuento del producto.
