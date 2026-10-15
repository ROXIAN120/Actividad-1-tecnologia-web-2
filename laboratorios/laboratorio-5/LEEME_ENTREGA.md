# Entrega — Laboratorio 5

Rodrigo Ledezma Sanchez · Grupo HTML · 14/10/2026

Incluye los seis puntos obligatorios resueltos, hoja A–F y bitácora. Los retos opcionales (Socio, venta persistida y resumen por categoría) no están implementados.

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 5"
php demo.php
php verificar.php
php -S 127.0.0.1:8005 -t public
```

Abrir <http://127.0.0.1:8005/> y la ficha de un producto desde el catálogo.

Alternativa Docker autocontenida: `docker compose up -d --build`, con la página en 8005. Incluye Dockerfile local y no necesita las carpetas externas del ZIP. Esta alternativa se validó con `docker compose config`; las pruebas de comportamiento se realizaron con PHP nativo.

Evidencias recibidas y guardadas: `evidencias/CAPTURA_VERIFICADOR.png` (14/14), `evidencias/CAPTURA_DEMO.png` (diez pasos) y `evidencias/CAPTURA_CATALOGO.png`. Las excepciones de los pasos 4–6 son controles esperados, no fallos de ejecución. La captura del catálogo usa el puerto 8006; el puerto es configurable y no altera su contenido.

Entregar `src/Producto.php`, `src/Catalogo.php`, hoja y bitácora, junto con el proyecto para ejecutarlo. Para la integración de Actividad 1, adjuntar también las entregas independientes de los laboratorios 1–4 con sus evidencias.
