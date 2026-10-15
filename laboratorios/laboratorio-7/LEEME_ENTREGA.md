# Laboratorio 7 — Ejecución y entrega

Rodrigo Ledezma Sanchez · Grupo HTML

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 7"
composer dump-autoload
php verificar.php
php -S 127.0.0.1:8007 -t public public/index.php
```

El último argumento es obligatorio: actúa como router para las rutas limpias.

Evidencia del error inesperado: `evidencias/CAPTURA_ERROR_500_REFERENCIA.png` muestra la referencia `b95e59a5` sin detalles internos; `CAPTURA_REGISTRO_ERROR_500.png` muestra la misma referencia con la causa en el registro privado. `CAPTURA_PRUEBA_500_Y_LIMPIEZA.png` documenta el arranque en 8017 leyendo un Markdown como JSON y la limpieza de `CATALOGO_ARCHIVO`. No se alteró el catálogo real. Queda cubierta la captura específica del 500 exigida en la guía.

Alternativa Docker: `docker compose run --rm php composer dump-autoload`, seguido de `docker compose up -d --build`. La configuración usa un Dockerfile local basado en el del docente; el flujo efectivamente probado fue PHP nativo en PowerShell.

Vistas: <http://127.0.0.1:8007/>, `/producto/MIE-1K`, `/cotizacion` y `/api/articulos`.

Implementación obligatoria completa; verificador 15/15. Evidencias recibidas del estudiante: `CAPTURA_VERIFICADOR.png`, `CAPTURA_CATALOGO.png`, `CAPTURA_BUSQUEDA_MIEL.png`, `CAPTURA_COTIZACION_45.png`, `CAPTURA_COTIZACION_636.png`, `CAPTURA_CONFLICTO_409.png` y `CAPTURA_VALIDACION_NEGATIVA.png`, guardadas en `evidencias/`, junto con el resultado textual del verificador. La cotización requerida muestra 12 MIE-500 por Bs 486,00 y 1 SRV-EXT por Bs 150,00, total Bs 636,00. El conflicto muestra 233 unidades de MIE-1K solicitadas frente a 18 existentes. La validación conserva -1 y muestra el error de campo; su captura no muestra el estado HTTP en DevTools, aunque el verificador confirma 422. Las capturas solicitadas del verificador y la página, más las demostraciones manuales de cotización y errores, están guardadas. `vendor` se genera localmente y se excluye de Git.
