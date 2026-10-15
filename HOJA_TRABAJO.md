# Laboratorio 9 — Repositorio, paginación, filtros y socios

Nombre: Rodrigo Ledezma Sanchez · Grupo HTML · Fecha: 14/10/2026

## Parte A — Del SQL al repositorio

1. `ProductoConsultas` del lab 8 entrega arreglos; `ProductoRepositorioMysql` devuelve objetos `Producto` con su `Socio`, o un objeto `Pagina` con la colección y los datos de navegación.
2. `hidratar()` transforma una fila del JOIN en objetos tipados: crea el socio, convierte categoría a enum y conserva DECIMAL como cadena.
3. El controlador depende del contrato `ProductoRepositorio`, por lo que puede recibir otra implementación sin cambiar sus operaciones de productos.
4. La versión en memoria permite pruebas de lógica sin MySQL o una demostración con datos temporales. No ofrece persistencia entre procesos y no sustituye las pruebas de SQL real.

## Parte B — Paginación

1. El código inicial no limita la consulta y devuelve las 36 filas, aunque indica una sola página. Es una descripción de la versión inicial, no una captura de ejecución previa.
2. Con 36 y 10 por página hay 4 páginas; con 0 hay al menos 1 para simplificar la navegación. `ceil` reserva una página para el resto; `round` puede omitirlo.
3. Para página 3, `LIMIT=10` y `OFFSET=(3-1)*10=20`.
4. `bindValue` con `PDO::PARAM_INT` fija el tipo entero de límite y desplazamiento; el arreglo de `execute` no permite indicar ese tipo explícitamente. Se evita que se traten como cadenas, especialmente al cambiar opciones de emulación.
5. COUNT calcula el total filtrado antes de limitar las filas; permite construir el rango, páginas y enlaces.

## Parte C — Orden seguro y URL

1. En el inicio, `stock_desc` se convertía en un fragmento SQL aceptado aunque no fuera una opción pública; una carga con `p.precio; DROP TABLE productos` ocasionaba error 500 registrado. Ahora todo orden desconocido se convierte en `nombre` y solo se interpolan fragmentos constantes de una lista permitida.
2. En el caso de la guía, la tabla no se borraba: la cadena producía SQL inválido y no ejecutaba otra sentencia. Eso no vuelve seguro el diseño: concatenar estructuras SQL controladas por el cliente permite otros ataques.
3. Un marcador representa un valor, no un nombre de columna ni una palabra clave. `ORDER BY :orden` no selecciona dinámicamente una columna de forma segura; debe usarse una lista de fragmentos conocidos.
4. `pagina=-5` y `pagina=abc` pasan a 1. `pagina=99` se ajusta a la última página disponible (4 con los datos iniciales). Un filtro sin resultados conserva página 1.

## Parte D — Filtros y relación 1 a N

1. Hay 10 productos de Cera; Cera junto con `vela` deja 2. Ambas condiciones se aplican en COUNT y en la consulta paginada.
2. `http_build_query` codifica filtros y número de página en una URL. El escape de la vista convierte `&` a `&amp;` para incorporarlo correctamente al atributo HTML; el navegador interpreta el enlace con separadores normales.
3. Un socio tiene muchos productos; cada producto pertenece a un socio, mediante la clave foránea `productos.socio_id`. `/socios/3` muestra los 4 productos de Elena Masabí y el valor de su stock. Un socio inexistente responde 404.

## Parte E — Verificación

Resultado real de `php verificar.php`: **14 de 14 comprobaciones aprobadas con MySQL 8.4**. La salida está en `evidencias/RESULTADO_VERIFICADOR.txt`; prueba paginación, filtros, órdenes inválidos, socios, CRUD y equivalencia con la implementación en memoria.

## Reflexión

Para PostgreSQL o una API externa, crearía otra implementación de `ProductoRepositorio` y cambiaría el ensamblaje de dependencias en `public/index.php`. Las vistas y el controlador conservarían el contrato de productos. Los socios aún dependen de una clase concreta `SocioRepositorio` que usa PDO: también habría que sustituir esa clase o extraer una interfaz para desacoplarla. No basta cambiar una cadena de conexión para migrar todo el proyecto.
