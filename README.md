# Tecnología Web II — Laboratorios 1 al 9

**Grupo:** HTML<br>
**Integrante:** Rodrigo Ledezma Sanchez

Repositorio: <https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2>

## Actividad 02: De JSON a MySQL

La entrega final está en [`actividad-02/`](actividad-02), independiente del catálogo JSON y de los laboratorios históricos. Incluye CRUD de productos y socios, tabla de categorías, PDO preparado, validación, paginación numerada y por clave, importación JSON y configuración privada. Verificación final: **48/48** comprobaciones.

- [Informe final APA 7 con explicación de código y capturas](actividad-02/documentos/Informe_Final_Actividad_02.pdf)
- [Presentación explicativa para la defensa](actividad-02/documentos/Presentacion_Explicativa_Actividad_02.pptx) — versión ampliada con 28 diapositivas y notas del expositor.
- [Bitácora de IA consolidada](actividad-02/BITACORA_IA.md)
- [Guía de ejecución](actividad-02/README.md) y [guion de defensa](actividad-02/GUIA_DEFENSA.md)
- [Mediciones EXPLAIN](actividad-02/evidencias/EXPLAIN_MEDICIONES.json)

La aplicación final usa MySQL en 3309 y PHP en 8020. No publicar `.env`; generar las claves localmente con `php configurar.php`. La defensa oral y el envío al aula siguen a cargo del participante.

## Actividad 01: catálogo JSON integrado

La rama `main` contiene la aplicación final con las clases `Producto`, `Categoria` y `Catalogo`, formularios validados, fotografías seguras y persistencia JSON.

```powershell
php verificar.php
php verificar_poo.php
php -S 127.0.0.1:8004 -t public
```

Abrir <http://127.0.0.1:8004>. Las pruebas funcionales obtienen 13/13 y las pruebas adicionales de POO obtienen 10/10. El verificador restaura los datos y las fotos de prueba.

- [Informe PDF](informes/Informe_Actividad_01.pdf)
- [Bitácora de IA](BITACORA_IA.md)
- [Guion del video de 60 segundos](GUION_VIDEO_60_SEGUNDOS.md)

Pendiente de entrega: grabar el video y adjuntar su archivo o enlace en la plataforma del curso.

## Laboratorios

Los nueve proyectos están disponibles juntos en [`laboratorios/`](laboratorios). Cada carpeta es independiente y contiene código, hoja de trabajo, bitácora y evidencias. No copiar el código de un laboratorio encima de otro: las arquitecturas evolucionan.

| Lab | Carpeta | Tema | Verificador documentado | Puerto web |
|---|---|---|---|---|
| 1 | [laboratorio-1](laboratorios/laboratorio-1) | Catálogo PHP, escape y formato | 12/12 | 8001 |
| 2 | [laboratorio-2](laboratorios/laboratorio-2) | Funciones e inventario | 12/12 | 8002 |
| 3 | [laboratorio-3](laboratorios/laboratorio-3) | Formularios, PRG y JSON | 13/13 | 8003 |
| 4 | [laboratorio-4](laboratorios/laboratorio-4) | Fotos y mantenimiento | 13/13 | 8004 |
| 5 | [laboratorio-5](laboratorios/laboratorio-5) | Clases y encapsulamiento | 14/14 | 8005 |
| 6 | [laboratorio-6](laboratorios/laboratorio-6) | Composer, herencia y contratos | 14/14 | 8006 |
| 7 | [laboratorio-7](laboratorios/laboratorio-7) | Router, excepciones y plantillas | 15/15 | 8007 |
| 8 | [laboratorio-8](laboratorios/laboratorio-8) | MySQL y PDO seguro | 15/15 | 8008 |
| 9 | [laboratorio-9](laboratorios/laboratorio-9) | Repositorios y paginación | 14/14 | 8009 |

Los resultados corresponden a verificaciones previas y capturas reales; no se restauran las bases automáticamente al descargar. El código de los verificadores oficiales se conserva sin modificaciones.

### Ejecutar una carpeta

Requisitos: PHP >=8.3 con mbstring, curl, fileinfo y pdo_mysql para 8–9; Composer 2 para 6–9; Docker Desktop para las bases MySQL.

Desde la carpeta del laboratorio, para 6–9 ejecutar primero `composer dump-autoload`.
Para 8–9 ejecutar también `docker compose up -d mysql` y esperar a `healthy` en `docker compose ps`.

```powershell
# Ejemplo: laboratorio 9, desde la raíz del repositorio
cd .\laboratorios\laboratorio-9
docker compose up -d mysql
docker compose ps
composer dump-autoload
php -S 127.0.0.1:8009 -t public public/index.php
```

Para 1–6 el servidor usa `php -S 127.0.0.1:PUERTO -t public`, sin el último `public/index.php`. Para 7–9 ese argumento es necesario como router. Sustituir PUERTO por el número de la tabla. Las instrucciones particulares están en `LEEME_ENTREGA.md` de cada carpeta.

**Cuidado:** `php verificar.php` en 8 y 9 restaura las tablas de ejemplo. Ejecutarlo solo en la base desechable del laboratorio, nunca con datos que deban conservarse. MySQL 8 usa 3307 y MySQL 9 usa 3308, con volúmenes independientes. Las claves de Compose son ejemplos locales, no secretos de producción. `vendor/` se reconstruye con Composer y no se distribuye; los registros internos tampoco se suben.

### Ramas individuales

- [`laboratorio-1`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-1): catálogo dinámico con PHP.
- [`laboratorio-2`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-2): funciones tipadas para el inventario.
- [`laboratorio-3`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-3): formulario, validación, PRG y JSON.
- [`laboratorio-4`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-4): fotografías seguras, edición y eliminación.
- [`laboratorio-5`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-5): modelo con objetos.
- [`laboratorio-6`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-6): herencia y Composer.
- [`laboratorio-7`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-7): excepciones y plantillas.
- [`laboratorio-8`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-8): MySQL y PDO.
- [`laboratorio-9`](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/tree/laboratorio-9): repositorio y paginación.

Cada rama contiene el código fuente, la hoja de trabajo, la bitácora de IA, el verificador oficial y sus capturas de evidencia.

## Alcance de la entrega

La integración de Actividad 1 incluye los laboratorios 1–5; los laboratorios 8–9 aportan la base de Actividad 2. Los ejercicios no sustituyen los módulos propios ni las exigencias adicionales del aula virtual. Los retos opcionales no se presentan como implementados. El video no está grabado: se conserva el guion. El informe de Actividad 1 no se presenta como informe de Actividad 2.

El usuario aclaró que prepara una solución docente. Las bitácoras conservan el historial real de asistencia de IA; las prohibiciones dirigidas al alumnado no se interpretan como falta del docente. No se atribuye autoría humana exclusiva al código generado con asistencia.

## Evidencias directas

- [Laboratorio 1 — catálogo](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-1/evidencias/CAPTURA_CATALOGO.png)
- [Laboratorio 1 — verificador 12/12](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-1/evidencias/CAPTURA_VERIFICADOR.png)
- [Laboratorio 2 — reporte](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-2/evidencias/CAPTURA_REPORTE.png)
- [Laboratorio 2 — verificador 12/12](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-2/evidencias/CAPTURA_VERIFICADOR.png)
- [Laboratorio 3 — catálogo](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-3/evidencias/CAPTURA_CATALOGO.png)
- [Laboratorio 3 — verificador 13/13](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-3/evidencias/CAPTURA_VERIFICADOR.png)
- [Laboratorio 4 — catálogo](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-4/evidencias/CAPTURA_CATALOGO.png)
- [Laboratorio 4 — verificador 13/13](https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2/blob/laboratorio-4/evidencias/CAPTURA_VERIFICADOR.png)
