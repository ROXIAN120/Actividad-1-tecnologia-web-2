# Defensa de la Actividad 02 (7–10 minutos)

1. **Problema y transición (1 min).** Explicar que JSON contiene el catálogo original, pero MySQL incorpora relaciones, restricciones y escrituras concurrentes. Mostrar origen_actividad01.json y sql/01-esquema.sql. La semilla ya incluye los seis productos originales; no presentar la simulación idempotente como seis inserciones nuevas.
2. **Modelo (1 min).** Categorías y socios tienen relación 1:N con productos. DECIMAL conserva importes exactos; UNIQUE protege el código; RESTRICT evita socios huérfanos. Mostrar el modelo del informe.
3. **Código (2 min).** Abrir Conexion.php, Repositorio.php y ProductoRepositorio.php. Seguir prepare → bindValue → execute. Explicar por qué ORDER BY usa una lista de constantes, no un parámetro ni entrada concatenada. Mostrar también SocioRepositorio.php y Validacion.php.
4. **Demostración (2 min).** Crear socio temporal; editarlo; registrar producto con ese socio; intentar borrar al socio (409); eliminar primero el producto y luego el socio. No modificar productos originales. Mostrar un formulario inválido (422).
5. **Seguridad (1 min).** Buscar `zzz' OR 1=1 -- -` y luego `<script>alert(1)</script>`. Debe haber cero coincidencias y texto escapado, no errores SQL ni ejecución de JavaScript. Explicar que pruebas concretas no certifican ausencia universal de fallos.
6. **Rendimiento (1 min).** Mostrar EXPLAIN_MEDICIONES.json: índice por socio lee cuatro filas frente a 36; clave ID examina diez frente a 30 en el ejemplo de OFFSET. Las medianas de red locales no demuestran menor latencia con solo 36 registros. El modo por clave ordena solo por ID y no ofrece salto directo de página.
7. **Cierre (1 min).** Ejecutar `php pruebas/verificar.php` en otra terminal; mostrar 48/48, repositorio y bitácora de IA. Aclarar alcance local sin autenticación, defensa pendiente y envío al aula manual.

## Preguntas esperables

- **¿Por qué no concatenar el orden recibido?** Los marcadores representan valores; las columnas y direcciones se eligen de fragmentos constantes permitidos.
- **¿Qué hace PDO ante una FK inválida?** Lanza una excepción; el repositorio convierte conflictos conocidos en HTTP 409 y conserva la causa para diagnóstico privado.
- **¿Por qué validar también en SQL?** El formulario ayuda al usuario; CHECK y FK mantienen integridad ante otros clientes y carreras de escritura.
- **¿Qué diferencia hay entre SQLi, XSS y CSRF?** SQLi altera instrucciones de base de datos; XSS ejecuta contenido en el navegador; CSRF fuerza solicitudes en una sesión. Se controlan en capas distintas.
- **¿Dónde están las claves?** En .env local ignorado o variables del entorno; config/bd.php queda fuera de public. No mostrar el archivo en la defensa.
- **¿Qué parte hizo la IA?** La implementación final, pruebas, navegación de evidencia y redacción se realizaron con asistencia de Codex; el participante debe revisar el código y defenderlo. Ver bitácora sin reconstrucciones ficticias de mensajes históricos.
- **¿Es un sistema productivo?** No. Faltan autenticación, roles, despliegue TLS, políticas de respaldo y validación de carga; no son requisitos de esta actividad local.

## Capturas reproducibles

Con el servidor de README activo, abrir estas rutas y capturar con Win+Mayús+S:

```text
http://127.0.0.1:8020/productos
http://127.0.0.1:8020/productos?pagina=2
http://127.0.0.1:8020/productos?categoria=3&q=vela
http://127.0.0.1:8020/productos?q=zzz%27%20OR%201%3D1%20--%20-
http://127.0.0.1:8020/productos?q=%3Cscript%3Ealert(1)%3C%2Fscript%3E
http://127.0.0.1:8020/productos?modo=cursor&despues=10
http://127.0.0.1:8020/socios
```

Para la consola: `docker compose ps`, `php pruebas/verificar.php` y `php pruebas/medir.php`. No mostrar .env. Las capturas del informe ya están incluidas, no es obligatorio repetirlas salvo para la defensa.
