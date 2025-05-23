<?php

use App\Models\Juego;

$css = 'perfil';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<div id="perfil">
    <!-- <img src="<?php echo $perfil["background_image"]?>" alt=""> -->
    <h2><?php echo $perfil['nick'] ?></h2>
</div>

<div class="juegos">
    <h3>Wishlist</h3> <!-- Aqui va la lista seleccionada en el perfil --> 
    <div>
        <?php 
        
            foreach ($perfil['wishlist'] as $juego){
        ?>
                <div class="juego">
                    <?php 
                    $ob_juego = new Juego;
                    $juego = $ob_juego->getById($juego);
                    if($juego['Imagen'] == null){
                        $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                    } 
                    ?>
                    <img src="<?php echo $juego['Imagen'] ?>" alt="">
                    <h4><?php echo $juego['Nombre'] ?></h4>
                </div>
        <?php
            }
        ?>
    </div>
</div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>