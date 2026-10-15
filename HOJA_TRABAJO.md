# Laboratorio 5 — El catálogo con objetos

Nombre: Rodrigo Ledezma Sanchez · Grupo: HTML · Fecha: 14/10/2026

## Parte A — Antes de completar los cambios

1. La venta de 5 unidades cuando quedaban 2 no generaba excepción: el stock quedaba en -3. Después la demo asignaba 1000 directamente, por lo que el paso 7 mostraba 1010 al reponer 10.
2. Los pasos 5 y 6 no imprimían advertencias: se aceptaban la modificación externa del stock y el constructor con código y precio inválidos.
3. El inventario mostraba Bs 0,00; era incorrecto. El valor de los datos originales es Bs 5.030,00.
4. Se podrían prometer productos inexistentes, alterar existencias sin control, registrar precios negativos y sobrescribir un producto con otro del mismo código.

## Parte B — Un objeto que se defiende solo

1. Salida real después de completar las reglas:

```text
4) Vender 5 más: Stock insuficiente de MIE-500: hay 2, se pidieron 5.
5) $p->stock = 1000: Cannot access private property Producto::$stock
6) Objeto inválido: Código inválido: miel
```

2. Validar en el constructor asegura las reglas al crear el objeto desde cualquier página, consola o lectura JSON. La validación del formulario sigue siendo útil para explicar errores al usuario.
3. `private` permite acceso solo dentro de la clase: el stock cambia con `vender()` y `reponer()`. `private readonly`, como el código, permite inicializar una vez e impide reasignaciones. `public` permite acceso externo: así era el stock inicial y por eso se podía alterar sin reglas. En la versión final no quedan propiedades públicas mutables; las constantes públicas `FORMATO_CODIGO` y `STOCK_BAJO` son accesibles, pero son constantes, no propiedades.
4. `InvalidArgumentException` indica un argumento inválido, como cantidad cero o código mal formado. `DomainException` indica que una operación con argumentos válidos rompe una regla del negocio, como vender más unidades de las existentes o repetir un código.

## Parte C — El catálogo

1. Antes de rechazar duplicados, agregar `MIE-500` sobrescribía la miel original. Quedaban 6 productos y la búsqueda devolvía `Otra miel`. Ahora se lanza `DomainException` antes de modificar la colección.
2. El valor es 5030.0, equivalente a Bs 5.030,00, igual que en el laboratorio 2.
3. `<=>` devuelve -1 si el valor izquierdo es menor, 0 si son iguales y 1 si es mayor. `usort` lo utiliza para ordenar ascendente o descendente sin alterar la colección original.
4. `desdeArchivo()` es una fábrica estática: crea el catálogo a partir de JSON y puede invocarse sin contar previamente con una instancia; no necesita `$this`.

## Parte D — Objetos y referencias

1. `$otra = $p` copia el identificador del mismo objeto. Reponer mediante `$otra` cambia el objeto que también observa `$p`: ambos muestran 12 y `$otra === $p` es verdadero.
2. `clone` crea otro objeto. Reponer 100 en la copia deja 112 en ella y 12 en el original. Es una copia superficial: la categoría sigue siendo compartida, algo seguro aquí porque es inmutable. Lo usaría para simular cambios sin alterar el producto original.

## Parte E — Verificación

`php verificar.php`: **14 de 14 comprobaciones**. `php demo.php` confirma el stock protegido, las excepciones, referencias y clonación. El verificador también comprueba el catálogo HTTP, la ficha de MIE-1K con precio de feria Bs 76,50 y la respuesta 404 para un código inexistente.

## Parte F — Integración de los laboratorios 1 a 5

| Lab | Concepto principal | Aplicación en el laboratorio 5 |
|---|---|---|
| 1 | PHP genera HTML y escapa datos | Las páginas recorren objetos y escapan nombres antes de mostrarlos. |
| 2 | Funciones tipadas, arreglos y cadenas | Métodos tipados calculan inventario; `array_map`, filtros, `match` y ordenamiento operan sobre objetos. |
| 3 | Persistencia JSON y organización | `desdeArchivo()` y `guardarEn()` convierten entre JSON y objetos; `cargar.php` reúne dependencias. Los formularios PRG del lab 3 no están presentes en este inicio del lab 5. |
| 4 | Separar archivos públicos de datos internos | JSON y clases permanecen fuera de `public/`. La subida de fotos del lab 4 no está implementada en el inicio del lab 5 y se conserva en su entrega independiente. |
| 5 | Encapsulamiento y composición | `Producto` protege stock y reglas; `Catalogo` contiene productos y categorías y rechaza duplicados. |

Para la entrega integrada se deben adjuntar las carpetas de los laboratorios 1 a 5 y sus evidencias. Esta carpeta contiene el laboratorio 5; las anteriores permanecen en `tarea 1` a `tarea 4`.

## Reflexión

La función `valor_inventario()` recibía arreglos y calculaba un total. `Catalogo::valorInventario()` consulta su propia colección y delega en `Producto::valorEnStock()`. La ventaja principal es reunir datos y reglas y proteger las modificaciones, no simplemente cambiar una función por un método. El resultado monetario sigue siendo el mismo.
