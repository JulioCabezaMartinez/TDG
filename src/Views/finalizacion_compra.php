<?php
$css = 'finalizacion_compra';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<div>
    <a class="enlace" href="/ventas">< Back to Shop</a>
</div>

<div class="detalles_compra">

    <h3>✅ Purchase Completed!</h3>

    <img class="imagen_venta" src="/public/IMG/productos<?php echo $producto["img_venta"] ?>" alt="">

    <h4><?php echo $producto["Titulo"] ?></h4>

    <?php

    if($producto["id"]==-1){
    ?>
        <p>Enjoy the benefits of TDG PREMIUM!</p>
    <?php
    }else{
    ?>
        <p>Your product will be shipped to your home.</p>
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