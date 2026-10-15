# Entrega — Laboratorio 6

Rodrigo Ledezma Sanchez · Grupo HTML · 14/10/2026

Requiere PHP >= 8.3 y Composer 2. Incluye los seis puntos obligatorios resueltos, hoja A–F, diagrama y bitácora. Los retos opcionales (Canasta, Perecedero y script Composer) no están implementados.

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 6"
composer dump-autoload
php demo.php
php verificar.php
php -S 127.0.0.1:8006 -t public
```

Abrir <http://127.0.0.1:8006/> y <http://127.0.0.1:8006/api.php>.

Alternativa Docker autocontenida: `docker compose run --rm php composer dump-autoload`, seguido de `docker compose up -d --build`, con la página en 8006. Incluye Dockerfile local y no necesita carpetas externas del ZIP. Esta alternativa se validó con `docker compose config`; las pruebas de comportamiento se realizaron con PHP nativo.

Capturas recibidas: `evidencias/CAPTURA_VERIFICADOR.png` (14/14), `evidencias/CAPTURA_CATALOGO.png` (8 artículos, 7 disponibles e inventario Bs 5.030,00) y `evidencias/CAPTURA_API_JSON.png` (respuesta de `/api.php` con productos y comienzo de los servicios serializados en JSON). La captura de la API muestra una parte de la respuesta desplazable; el verificador comprueba los 8 artículos. Quedan completas las capturas solicitadas del laboratorio 6.

La guía pide entregar el proyecto sin `vendor/`. Esa carpeta está generada localmente para ejecutar el proyecto; excluirla al comprimir o subir. En otra máquina se reconstruye con `composer dump-autoload`. No se instalaron dependencias externas.
