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
    <h3>Wishlist</h3>
    <div>
        <?php 
        if(count($perfil['wishlist'])<=4){
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
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['wishlist'][$i];
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
            echo '<a class="view_all" href="/">View All</a>';
        }
        ?>
    </div>
</div>

<div class="juegos">
    <h3>Playing</h3>
    <div>
        <?php 
        if(count($perfil['playing'])<=4){
            foreach ($perfil['playing'] as $juego){
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
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['playing'][$i];
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
            echo '<a class="view_all" href="/">View All</a>';
        }
        ?>
    </div>
</div>

<div class="juegos">
    <h3>Completed</h3>
    <div>
        <?php 
        if(count($perfil['completed'])<=4){
            foreach ($perfil['completed'] as $juego){
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
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['completed'][$i];
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
                    <br>
                </div>
                <?php
            }
            echo '<a class="view_all" href="/">View All</a>';
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