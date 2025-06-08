<?php
$css = 'ventas_perfil';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<div id="perfil">
    <img src="/public/IMG/<?php echo $perfil["Imagen_usuario"] ?>" alt="" class="perfil_imagen">
    <h2><?php echo $perfil['Nick'] ?></h2>
</div>

<div class="ventas_compras">
    <h3>Purchases</h3>
    <div class="paginacionCompras"></div>
    <div id="lista_compras">
        
    </div>
</div>

<div class="ventas_compras">
    <h3>Sales</h3>
    <div class="paginacionVentas"></div>
    <div id="lista_ventas">
       
    </div>
</div>

<script src="/public/JS/venta_perfil.js"></script>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>