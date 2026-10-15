# Laboratorio 9 — Ejecución y entrega

Rodrigo Ledezma Sanchez · Grupo HTML

Requiere Docker Desktop encendido, PHP >=8.3 con pdo_mysql y Composer 2. MySQL 8.4 usa 127.0.0.1:3308, separado de la tarea 8 para que sus restauraciones no interfieran.

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 9"
docker compose up -d mysql
docker compose ps
composer dump-autoload
php verificar.php
php -S 127.0.0.1:8009 -t public public/index.php
```

Esperar a MySQL `healthy`. Abrir <http://127.0.0.1:8009/productos>, `/productos?categoria=Miel&orden=precio_desc&pagina=2`, `/socios` y `/socios/3`.

Hay 36 productos y 8 socios iniciales. El verificador restaura sus tablas de ejemplo al empezar y terminar. La base y el volumen son exclusivos de esta tarea; no usar esta configuración para datos reales.

Entregar proyecto sin `vendor/`, hoja, bitácora, captura del verificador 14/14 y lista paginada con filtros. Los retos opcionales (interfaz de socios, tamaños 10/25/50 y cursor) no están implementados. La configuración alternativa del docente se conserva en `docker-compose.original.yml`.

## Evidencias recibidas

Capturas reales proporcionadas por el usuario y guardadas en `evidencias/`:

- `CAPTURA_VERIFICADOR.png`: 14/14 comprobaciones y MySQL 8.4 healthy en el puerto 3308.
- `CAPTURA_PAGINA_1.png`: mostrando 1–10 de 36, página 1 de 4.
- `CAPTURA_PAGINA_2.png`: mostrando 11–20 de 36, página 2 de 4.
- `CAPTURA_FILTRO_CERA_VELA.png`: categoría Cera y búsqueda vela, 2 resultados.
- `CAPTURA_PRECIO_DESCENDENTE.png`: orden de mayor a menor, con CER-BLO a Bs 540,00 primero.
- `CAPTURA_SOCIO_3.png`: Elena Masabí, 4 productos y valor en stock Bs 2.847,50.

Quedan cubiertas las capturas solicitadas del verificador y del listado paginado con filtros, además del ordenamiento y el detalle del socio. Las páginas 1 y 2 capturadas no tienen filtros activos; la conservación de filtros al paginar está comprobada por el verificador, no por esas dos imágenes.

Detener la base: `docker compose stop mysql`. Reiniciar: `docker compose start mysql`.
