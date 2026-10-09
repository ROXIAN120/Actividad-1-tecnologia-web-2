# Entrega del Laboratorio 1

## Contenido

- `public/index.php`: catálogo dinámico resuelto.
- `public/index-estatico.html`: versión antigua conservada únicamente para comparación.
- `datos.php`: fuente de los seis productos, fuera de la carpeta pública.
- `verificar.php` y `Verificador.php`: comprobación automática.
- `HOJA_TRABAJO.md`: partes A a E y reflexión respondidas.
- `BITACORA_IA.md`: declaración detallada del uso de IA.
- `evidencias/CAPTURA_CATALOGO.png`: catálogo funcionando en el navegador.
- `evidencias/CAPTURA_VERIFICADOR.png`: resultado 12 de 12.

## Comprobar la entrega

Desde esta carpeta:

```powershell
php verificar.php
php -S 127.0.0.1:8000 -t public
```

Luego abre `http://127.0.0.1:8000/` en el navegador. El primer comando debe terminar con `Resultado: 12 de 12 comprobaciones`.
