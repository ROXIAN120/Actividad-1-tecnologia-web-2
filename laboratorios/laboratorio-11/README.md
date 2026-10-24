# Laboratorio 11 - Transacciones, reportes y exportación CSV

Este laboratorio implementa el registro transaccional de ventas y acopios sobre la aplicación Apicultores Flor de la Chiquitanía.

## Funcionalidades verificadas

- Venta atómica: si una línea falla, se ejecuta `rollBack` y no queda una venta parcial.
- Descuento de stock con condición suficiente y respuesta 409 cuando no hay unidades disponibles.
- Acopio que incrementa stock y registra el movimiento asociado al socio dueño.
- Reportes de ventas por mes, top de productos, socios con `HAVING` y productos sin ventas.
- Validación de fechas y restricción de reportes al rol Administrador.
- CSV UTF-8 con BOM, compatible con Excel y protegido ante fórmulas peligrosas.

## Ejecución

```powershell
docker compose up -d
composer dump-autoload
php verificar.php
php -S 127.0.0.1:8011 -t public public/index.php
```

Abra `http://127.0.0.1:8011`. Espere a que MySQL esté `healthy` antes de ejecutar las pruebas.

## Resultado

El verificador finaliza con **15 de 15 comprobaciones aprobadas**. Las evidencias funcionales se almacenan en `evidencias/`.
