# Bitácora consolidada de IA: actividades 1 y 2

Responsable: Rodrigo Ledezma Sanchez. Grupo HTML. UPDS, Tecnología Web II.
Actualización: 16 de octubre de 2026. Herramienta: OpenAI Codex.

## Transparencia y alcance

Esta bitácora declara asistencia sustancial de IA en programación, depuración, pruebas, organización del repositorio y redacción. No se atribuye autoría humana exclusiva. El usuario indicó que la restricción de IA de sus guías está destinada a sus estudiantes y solicitó una solución completa. No se reconstruyen prompts, horas ni decisiones humanas que no constan en el historial disponible.

La revisión documental del 15 de octubre autorizaba solamente analizar y registró pendientes. El 16 de octubre el usuario cambió expresamente el alcance: pidió completar la actividad, capturas, explicación de código, informe APA 7 y presentación. Por eso los PDF anteriores de revisión no son la entrega final y sus pendientes no describen la versión final nueva.

## Etapas anteriores (síntesis del registro conservado)

| Etapa | Intervención de IA | Comprobación histórica disponible |
|---|---|---|
| Laboratorio 1 | PHP inicial, salida escapada y formato | Bitácora del laboratorio y captura 12/12 |
| Laboratorio 2 | Funciones, inventario y arreglos | Bitácora y captura 12/12 |
| Laboratorio 3 | Validación, PRG y persistencia JSON | Bitácora y captura 13/13 |
| Laboratorio 4 | Fotos, edición y eliminación | Bitácora y captura 13/13 |
| Laboratorio 5 | Encapsulamiento y modelo POO | Bitácora y captura 14/14 |
| Laboratorio 6 | Composer, herencia, enum, trait y contratos | Captura 14/14, catálogo y API JSON |
| Laboratorio 7 | Router, plantillas y excepciones | Captura 15/15 y error 500 con referencia de log |
| Laboratorio 8 | PDO, consultas preparadas y CRUD de productos | Captura 15/15 y búsquedas antes/después SQLi |
| Laboratorio 9 | Repositorios, filtros, páginas y ficha de socio | Captura 14/14 y navegación del sistema |
| Publicación 1–9 | Organización y publicación de carpetas/ramas | Revisión base 399257b del repositorio |
| Revisión Actividad 2 | Comparación con guía y rúbrica | Identificó falta de CRUD de socios, tabla categorías y secretos efectivos |

Los resultados anteriores son históricos, no una nueva ejecución de esos verificadores en esta entrega. Se conservan las bitácoras particulares en laboratorios/laboratorio-N/BITACORA_IA.md y sus evidencias. Las capturas históricas fueron suministradas por el usuario.

## Trabajo final del 16 de octubre de 2026

**Solicitud real:** “Quiero que completes la actividad 2, con todo lo que pide mas las capturas mas explicacion de codigo, con formato apa 7”. La consigna incluyó diseño SQL, CRUD de ambas entidades, consultas preparadas, validación, paginación, SQLi, XSS y mediciones EXPLAIN para el nivel superior.

**Implementación asistida:** aplicación independiente con base propia en puerto 3309; tres tablas normalizadas; repositorios ProductoRepositorio, SocioRepositorio y CategoriaRepositorio; conexión PDO nativa; secretos aleatorios en .env ignorado; vistas escapadas; CSRF; rutas y excepciones HTTP; importación JSON transaccional, idempotente y con simulación; paginación numerada y alternativa por clave ID.

**Comprobación realizada por la IA con herramientas:** 48 pruebas automatizadas sobre PHP 8.5.8 y MySQL 8.4.11, con fixtures temporales y limpieza selectiva. Incluye inserción, edición y borrado de ambas entidades por repositorio y HTTP, SQLi, XSS reflejado/almacenado, restricciones de base, CSRF, archivos privados y migración JSON. Resultados completos en evidencias/RESULTADOS_PRUEBAS.txt y .json.

**Capturas nuevas:** navegación real automatizada por el asistente en http://127.0.0.1:8020. Son archivos JPG de la aplicación en ejecución, no ilustraciones generadas. Se crearon y eliminaron socio y producto de demostración. Las imágenes de pasos intermedios pueden mostrar nueve socios o un producto temporal; la base final vuelve a ocho socios y 36 productos.

**Mediciones:** EXPLAIN y EXPLAIN ANALYZE sobre consultas concretas, con 31 repeticiones para medianas. El índice por socio reduce lectura observada de 36 a cuatro filas y el ejemplo por clave de 30 a diez. Las medianas medidas no prueban mayor velocidad para esta base pequeña y el informe lo declara.

**Correcciones tras revisión:** se completaron los requisitos ausentes de los ejercicios originales sin sobrescribirlos. El modo por clave muestra explícitamente orden por ID, pues no representa el orden por nombre ni permite saltar a un número de página. El importador documenta omisiones de productos ya existentes, sin afirmar inserciones inexistentes.

**Documentación asistida:** informe final APA 7 con código y evidencia, presentación, instrucciones de ejecución y guía de defensa. Las referencias técnicas corresponden a documentación primaria de PHP, Oracle MySQL y OWASP. La información de autor, curso y docente procede del material local, sin inventar integrantes adicionales.

## Revisión humana pendiente

Rodrigo debe revisar datos de portada e integrantes, comprender el código, ejecutar la demostración y defender el informe. La asistencia no equivale a defensa realizada ni envío al aula virtual. Las capturas y pruebas acreditan escenarios concretos, no una certificación universal de seguridad o capacidad productiva. El proyecto local carece de autenticación y roles.

## Referencias

OpenAI. (2026). *Codex* [Asistente de inteligencia artificial]. https://openai.com/codex/

ROXIAN120. (2026). *Actividad-1-tecnologia-web-2* [Código fuente]. GitHub. https://github.com/ROXIAN120/Actividad-1-tecnologia-web-2
