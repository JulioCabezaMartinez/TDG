<?php

$css = 'perfil';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';

?>

<div id="perfil">
    <img src="/TDG/public/IMG/<?php echo $perfil["Imagen_usuario"] ?>" alt="" class="perfil_imagen">
    <h2><?php echo $perfil['Nick'] ?></h2>
</div>

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
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>