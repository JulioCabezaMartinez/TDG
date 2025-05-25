<?php

use App\Models\Juego;


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
    <?php
    // echo var_dump($perfil["wishlist"]);
    ?>
    <div>
        <?php 
        if(count($perfil['wishlist'])<=6){
            foreach ($perfil['wishlist'] as $juego){
        ?>
        
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "/TDG/public/IMG/default-game.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                    </a>
                </div>
        <?php
            }
        }else{
            $wishlist = array_values($perfil['wishlist']);
            for($i=0; $i<6; $i++){
                $juego = $wishlist[$i];
                ?>
                    <div class="juego">
                        <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                            <?php 
                            
                            
                            if($juego['Imagen'] == null){
                                $juego['Imagen'] = "/TDG/public/IMG/default-game.jpg"; // Si no hay imagen, asignar una por defecto
                            } 
                            ?>
                            <img src="<?php echo $juego['Imagen'] ?>" alt="">
                            <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                        </a>
                    </div>
                <?php
            }
        ?>
            <a class="view_all boton-perso" href="#">View All</a>
        <?php
        }
        ?>
    </div>
</div>

<div class="juegos">
    <h3>Jugando</h3>
    <div>
        <?php 
        if(count($perfil['playing'])<=4){
            foreach ($perfil['playing'] as $juego){
        ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                    </a>
                </div>
        <?php
            }
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['playing'][$i];
                ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                    </a>
                </div>
                <?php
            }
            echo '<a class="view_all" href="/">View All</a>';
        }
        ?>
    </div>
</div>

<div class="juegos">
    <h3>Completados</h3>
    <div>
        <?php 
        if(count($perfil['completed'])<=4){
            foreach ($perfil['completed'] as $juego){
        ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                    </a>
                </div>
        <?php
            }
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['completed'][$i];
                ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                        <br>
                    </a>
                </div>
                <?php
            }
            echo '<a class="view_all" href="/">View All</a>';
        }
        ?>
    </div>
</div>

<div class="juegos">
    <h3>Pendientes</h3>
    <div>
        <?php 
        if(count($perfil['backlog'])<=4){
            foreach ($perfil['backlog'] as $juego){
        ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                    </a>
                </div>
        <?php
            }
        }else{
            for($i=0; $i<4; $i++){
                $juego = $perfil['backlog'][$i];
                ?>
                <div class="juego">
                    <a href="/TDG/juegos/view?id=<?php echo $juego["id"] ?>">
                        <?php 
                        
                        
                        if($juego['Imagen'] == null){
                            $juego['Imagen'] = "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg"; // Si no hay imagen, asignar una por defecto
                        } 
                        ?>
                        <img src="<?php echo $juego['Imagen'] ?>" alt="">
                        <h4 class="nombreJuego"><?php echo $juego['Nombre'] ?></h4>
                        <br>
                    </a>
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