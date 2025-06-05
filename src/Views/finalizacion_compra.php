<?php
$css = 'finalizacion_compra';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<div>
    <a class="enlace" href="/ventas">< Volver a Tienda</a>
</div>

<div class="detalles_compra">

    <h3>✅ Compra Finalizada</h3>

    <img class="imagen_venta" src="/public/IMG/productos<?php echo $producto["img_venta"] ?>" alt="">

    <h4><?php echo $producto["Titulo"] ?></h4>

    <?php

    if($producto["id"]==-1){
    ?>
        <p>¡Disfrute de las ventajas de las ventajas de TDG PREMIUM!</p>
    <?php
    }else{
    ?>
        <p>Su producto será enviado a su domicilio.</p>
    <?php
    }
    ?>
    
</div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>