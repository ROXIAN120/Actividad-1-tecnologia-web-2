# Bitácora de IA — Laboratorio 8

Nombre: Rodrigo Ledezma Sanchez · Grupo HTML · Fecha: 14/10/2026

## Consulta realizada

Se solicitó al asistente desarrollar el laboratorio con los archivos de inicio oficiales, revisar sus dependencias, completar la documentación y ejecutar el verificador.

## Sugerencia recibida y cambios

El asistente implementó consultas preparadas nativas, escape de comodines, traducción del error 1062, UPDATE limitado por código y respuesta 404 al borrar un código inexistente.

## Qué se revisó

Se contrastó la implementación con los verificadores y el inicio del docente. Las hojas distinguen los comportamientos iniciales descritos por el código de las comprobaciones realizadas sobre la solución. La configuración Docker se adaptó para ejecutar PHP desde PowerShell y MySQL en un contenedor independiente, sin depender del árbol de carpetas del ZIP.

## Comprobación

Se generó el autoload con Composer. El resultado real del verificador y su salida están documentados en `evidencias/RESULTADO_VERIFICADOR.txt`. Los comandos de reproducción están en `LEEME_ENTREGA.md`.

## Alcance del apoyo y reflexión

Actualización de evidencias (15/10/2026): el usuario proporcionó capturas manuales de cinco búsquedas en el inicio vulnerable (8018) y en la solución (8008): miel, zzz, porcentaje, carga de inyección y comilla sola. Se guardaron las diez imágenes y se contrastaron sus resultados con la tabla de `LEEME_ENTREGA.md`. La comilla provoca una página 500 en el inicio y 0 productos sin error visible en la solución; zzz devuelve 0 en ambas. No se volvió a ejecutar el verificador durante este cierre de capturas ni se restauraron las tablas.

La IA preparó código y borradores de respuestas. Esto se declara expresamente: no se presenta como una implementación sin ayuda ni como una defensa personal que ya se haya realizado. Las guías limitan pedir los TODO resueltos; este apoyo supera ese alcance. Antes de presentar corresponde revisar cada cambio y poder explicar sus razones.
