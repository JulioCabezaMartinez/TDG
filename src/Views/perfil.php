<?php

$css = 'perfil';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';

?>

<div id="perfil">
    <?php
        if($perfil["Premium"]==1){
    ?>
        <i class="fa-solid fa-crown"></i>
    <?php
        } 
    ?>
    <h2><?php echo $perfil['Nick'] ?></h2>

    <?php
        if($perfil["Premium"]==0){
    ?>
        <a href="/TDG/perfil/Premium">
        <button class="getPremium boton-perso">Conseguir Premium <i class="fa-solid fa-crown"></i></button>
        </a>
    <?php
        } 
    ?>
</div>

<a href="/TDG/perfil/ventas">
    <button class="boton-perso">Ver Ventas y Compras</button>
</a>

<div class="juegos">
    <h3>Deseados</h3>
    <div class="paginacionWishlist"></div>
    <div id="wishlist"></div>
</div>

<div class="juegos">
    <h3>Jugando</h3>
    <div class="paginacionPlaying"></div>
    <div id="playing"></div>
</div>

<div class="juegos">
    <h3>Completados</h3>
    <div class="paginacionCompleted"></div>
    <div id="completed"></div>
</div>

<div class="juegos">
    <h3>Pendientes</h3>
    <div class="paginacionBacklog"></div>
    <div id="backlog"></div>
</div>

<script src="/TDG/public/JS/perfil.js"></script>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>