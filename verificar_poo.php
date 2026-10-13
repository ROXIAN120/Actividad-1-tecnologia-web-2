<?php
declare(strict_types=1);

require_once __DIR__ . '/src/Catalogo.php';

$correctas = 0;
$total = 0;

function comprobar(string $descripcion, callable $prueba): void
{
    global $correctas, $total;
    $total++;
    try {
        if ($prueba() !== true) {
            throw new RuntimeException('La condición no se cumplió.');
        }
        $correctas++;
        echo "✅ $descripcion", PHP_EOL;
    } catch (Throwable $error) {
        echo "❌ $descripcion — ", $error->getMessage(), PHP_EOL;
    }
}

comprobar('Existen las clases Producto, Categoria y Catalogo',
    fn(): bool => class_exists(Producto::class) && class_exists(Categoria::class) && class_exists(Catalogo::class));

comprobar('Producto usa promoción de propiedades en el constructor', function (): bool {
    $constructor = (new ReflectionClass(Producto::class))->getConstructor();
    return $constructor !== null
        && count(array_filter($constructor->getParameters(), fn(ReflectionParameter $p): bool => $p->isPromoted())) === 7;
});

comprobar('Catalogo encapsula la ruta JSON como propiedad privada promovida', function (): bool {
    $propiedad = (new ReflectionClass(Catalogo::class))->getProperty('archivo');
    return $propiedad->isPrivate() && $propiedad->isReadOnly() && $propiedad->isPromoted();
});

comprobar('Categoria acepta solamente las tres opciones del dominio', fn(): bool =>
    Categoria::esValida('Miel') && Categoria::esValida('Derivados')
    && Categoria::esValida('Cera') && !Categoria::esValida('Inventada'));

$producto = new Producto('POL-100', 'Polen seco 100 g', new Categoria('Derivados'), 32.5, 15, 'Élida Chuvé');
comprobar('Producto convierte sus datos a un arreglo listo para JSON', fn(): bool =>
    $producto->aArray()['precio'] === 32.5 && $producto->aArray()['socio'] === 'Élida Chuvé');

comprobar('Producto usa match para clasificar el stock', fn(): bool =>
    $producto->nivelStock() === 'Disponible'
    && (new Producto('ABC-00', 'Ejemplo', new Categoria('Miel'), 1.0, 0, 'Socio'))->nivelStock() === 'Agotado');

$temporal = tempnam(sys_get_temp_dir(), 'catalogo_');
if ($temporal === false) {
    throw new RuntimeException('No se pudo crear el archivo temporal.');
}
$catalogo = new Catalogo($temporal);
$catalogo->guardar([$producto]);
comprobar('Catalogo guarda y recupera objetos desde JSON', fn(): bool =>
    count($catalogo->todos()) === 1 && $catalogo->todos()[0] instanceof Producto);
comprobar('Catalogo busca por código', fn(): bool => $catalogo->buscarPorCodigo('POL-100')?->nombre === 'Polen seco 100 g');
comprobar('El operador nullsafe devuelve null si el producto no existe', fn(): bool => $catalogo->nombrePorCodigo('NO-EXISTE') === null);
comprobar('El JSON conserva Unicode y fracciones decimales', function () use ($temporal): bool {
    $json = (string) file_get_contents($temporal);
    return str_contains($json, 'Élida Chuvé') && str_contains($json, '32.5');
});
unlink($temporal);

echo "Resultado POO: $correctas de $total comprobaciones", PHP_EOL;
exit($correctas === $total ? 0 : 1);
