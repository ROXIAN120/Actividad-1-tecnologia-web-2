# Entrega del Laboratorio 3

## Contenido

- `src/validacion.php`: validación y normalización de los seis campos.
- `src/almacen.php`: lectura y escritura segura del JSON con `LOCK_EX`.
- `public/nuevo.php`: formulario POST, respuesta 422 y redirección 303 con PRG.
- `public/index.php`: catálogo y búsqueda mediante GET.
- `plantillas/`: cabecera y pie compartidos.
- `HOJA_TRABAJO.md`: partes A a E y reflexión contestadas.
- `BITACORA_IA.md`: registro honesto del apoyo utilizado.
- `verificar.php`: trece comprobaciones oficiales.

## Comandos

```powershell
cd "C:\Users\itman\Documents\Tecnologiaweb2\tarea 3"
php verificar.php
php -S 127.0.0.1:8002 -t public
```

Abrir `http://127.0.0.1:8002/nuevo.php`.
