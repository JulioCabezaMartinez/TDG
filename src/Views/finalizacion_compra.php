<?php
$css = 'finalizacion_compra';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<div>
    <a class="enlace" href="/TDG/ventas">< Volver a Tienda</a>
</div>

<div class="detalles_compra">
    <h3>✅ Compra Finalizada</h3>

    <img class="imagen_venta" src="/TDG/public/IMG/<?php echo $producto["img_venta"] ?>" alt="">

    <h4><?php echo $producto["Titulo"] ?></h4>

    <p>Su producto será enviado a su domicilio.</p>
</div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>