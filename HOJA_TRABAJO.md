# Laboratorio 2 — Hoja de trabajo: el lenguaje PHP al servicio del inventario

Nombre: ______________________   Fecha: 09/10/2026

## Parte A — Trampas del lenguaje

Se creó `pruebas.php` y se ejecutó con PHP 8.5.8. Los resultados fueron:

1. `0.1 + 0.2 == 0.3` produce `false` porque muchos decimales no tienen una representación binaria exacta y el resultado interno contiene una pequeña diferencia.
2. `round(0.1 + 0.2, 2) == 0.3` produce `true` porque el redondeo elimina esa diferencia para la comparación indicada.
3. `'1' == '01'` produce `true`: `==` hace conversión de tipos y compara ambos valores como números.
4. `'1' === '01'` produce `false`: `===` exige el mismo tipo y el mismo contenido.
5. `0 == 'a'` produce `false` en PHP 8; una cadena no numérica ya no se convierte en cero para esa comparación.
6. `null ?? 'sin dato'` devuelve `'sin dato'` porque el operador de coalescencia usa el valor de la derecha cuando el de la izquierda es `null` o no existe.
7. `strlen('miel ñ')` devuelve `7` bytes, mientras que `mb_strlen('miel ñ')` devuelve `6` caracteres.
8. `strtoupper('élida')` devuelve `éLIDA`, mientras que `mb_strtoupper('élida')` devuelve `ÉLIDA`, porque la función multibyte reconoce correctamente la letra acentuada.
9. `intdiv(17, 5)`, `17 % 5` y `2 ** 10` imprimen `3 2 1024`: cociente entero, residuo y potencia.

Conviene usar `===` para evitar conversiones implícitas inesperadas y las funciones `mb_*` para procesar correctamente texto UTF-8. Para el dinero conviene trabajar internamente con centavos enteros o con una biblioteca decimal, y formatear a dos decimales solamente al presentar el resultado.

## Parte B — switch frente a match

Con `$stock = '5'`, `switch` imprime `switch: coincide` porque compara de forma laxa y considera equivalentes la cadena `'5'` y el entero `5`. `match` imprime `match: no coincide` porque usa comparación estricta. El último `match`, al no tener un caso compatible ni `default`, lanza `UnhandledMatchError`.

Prefiero `match` para reglas cerradas porque compara de forma estricta, devuelve directamente un valor y obliga a considerar los casos no contemplados. Para rangos se puede usar `match (true)`.

## Parte C — Funciones de `src/inventario.php`

Se completaron las seis funciones obligatorias:

- `precio_con_descuento`: 0 %, 5 % o 10 % según la cantidad y cálculo monetario mediante centavos.
- `clasificar_stock`: `Agotado`, `Bajo`, `Normal` o `Alto` usando `match (true)`.
- `filtrar_por_categoria`: comparación sin distinguir mayúsculas y resultado reindexado.
- `valor_inventario`: suma de `precio × stock` con `array_map` y `array_sum`.
- `slug`: minúsculas, normalización de tildes y ñ, sustitución de símbolos y limpieza de guiones.
- `codigo_valido`: validación mediante `/^[A-Z]{3}-[A-Z0-9]{2,4}$/`.

El verificador oficial obtiene **12 de 12 comprobaciones**.

## Parte D — El reporte

1. El valor total del inventario es **Bs 5.030,00**. La miel de cusi `MIE-CUS` no suma nada porque su stock es `0`; al multiplicar precio por stock el resultado es cero.
2. Doce frascos de miel de 500 g cuestan **Bs 486,00**: `45 × 12` con 10 % de descuento. Seis frascos cuestan **Bs 256,50**: `45 × 6` con 5 % de descuento.
3. Al abrir `/reporte.php?categoria=<b>hola</b>` se muestra literalmente `Productos de <b>hola</b>` y no aparece ningún producto. Es seguro porque `e()` aplica `htmlspecialchars`, por lo que las etiquetas se convierten en texto y no se ejecutan como HTML.

También se comprobó que `?categoria=Miel` muestra los tres productos de miel y que `?orden=desc` coloca primero el producto de mayor precio.

## Parte E — Tipos estrictos

Al llamar `clasificar_stock('5')` desde `pruebas.php`, que contiene `declare(strict_types=1)`, aparece un `TypeError`: el primer argumento debe ser `int`, pero se entregó un `string`. Al ejecutar la llamada desde `pruebas_sin_strict.php`, PHP convierte la cadena numérica `'5'` al entero `5` y la función devuelve `Bajo`.

La declaración de tipos estrictos se aplica en el archivo que realiza la llamada. Por eso conviene mantenerla: los errores de tipos se detectan temprano en lugar de ocultarse mediante conversiones automáticas.

## Retos para nivel Estratégico

1. Se añadió el argumento opcional nombrado `esSocio`; cuando vale `true`, aplica un descuento del 15 %.
2. Se añadió `stock_por_socio()` y una tabla con las unidades agrupadas por socio en el reporte.
3. El cálculo de descuentos trabaja internamente con centavos enteros. También se añadió `precio_con_descuento_centavos()` para comparar de forma explícita los resultados sin errores binarios de punto flotante.

Los retos se validan con `php verificar_retos.php`.

## Reflexión

Separar la lógica en funciones pequeñas permite probar cada regla sin depender del HTML, reutilizarla en otras páginas y detectar con precisión dónde ocurre un error. Los tipos documentan lo que recibe y devuelve cada función, mientras que `strict_types` evita conversiones silenciosas. El reporte queda limitado a presentar datos y las reglas del inventario permanecen organizadas en un solo archivo.
