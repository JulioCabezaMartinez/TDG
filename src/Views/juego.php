<?php
$css = 'juego';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>
<div class="top_juego">
    <h1><?php echo $juego['Nombre'] ?></h1>

    <img class="imagen_juego" src="<?php echo $juego['Imagen'] ?>" alt="">

    <?php
    if(!empty($_SESSION)){
    ?>

    <div class='btn_listas'>
        <div class="boton btn_redondo wishlist">
            <p>Añadir a la lista de Deseados</p>
            <i class='fa-regular fa-heart btn_wishlist icono_ajustable'></i>
        </div>
        
        <div>

            <div class="boton btn_redondo backlog">
                <!-- <p>Añadir a la lista de Pendientes</p> -->
                <i class='fa-regular fa-clock icono_ajustable'></i>
            </div>

            <div class="boton btn_redondo completed">
                <!-- <p>Añadir a la lista de Completados</p> -->
                <i class='fa-regular fa-circle-check icono_ajustable'></i>
            </div>

            <div class="boton btn_redondo playing">
                <!-- <p>Añadir a la lista de Jugando</p> -->
                <i class='fa-regular fa-circle-play icono_ajustable'></i>
            </div>
            
        </div>

    </div>

    <?php
    }
    ?>
    
</div>


<div class="info_juego">
    <div class="caracteristicas_juego">

        <div class="generos_juego">
            <h3>Géneros</h3>
            <div class="distr-wrap">
                <?php
                    foreach ($generos as $genero) {
                ?>
                    <p class="btn_redondo"><?php echo $genero['Nombre'] ?></p>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="plataformas_juego">
            <h3>Plataformas</h3>
            <div class="distr-wrap">
                <?php
                    foreach ($plataformas as $plataforma) {
                ?>
                    <p><?php echo $plataforma["Nombre"] ?></p>
                <?php
                    }
                ?>
            </div>
        </div>
        <div>
            <h3>Año de Salida</h3>
            <p>
            <?php
                $fechaOriginal = $juego['Anyo_salida'];
                $fecha = new DateTime($fechaOriginal);
                echo $fecha->format('M j, Y'); // Un formato de fecha más legible.
            ?>
            </p>
        </div>

    </div>

    <div class="descripcion_juego">
        <h2>Descripción (Falta poner boton verMas)</h2>
        <?php echo $juego['Descripcion'] ?>
    </div>

</div>

<?php
    if(!empty($_SESSION)){
?>

<!-- Carrusel de ventas del Juego -->

<div class="ventas_juego">
    
    <a href="#"><h2 class="enlace">Ventas</h2></a> Me falta implementar el AJAX de los filtros.

    <div class="swiper">
        <div class="card-wrapper">
            <div class="card-list swiper-wrapper">

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego['Imagen']; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['Nombre'] ?></h3>
                        <p><strong><?php echo 30 ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego['Imagen']; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['Nombre'] ?></h3>
                        <p><strong><?php echo 20 ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego['Imagen']; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['Nombre'] ?></h3>
                        <p><strong><?php echo 12 ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego['Imagen']; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['Nombre'] ?></h3>
                        <p><strong><?php echo 43 ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego['Imagen']; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['Nombre'] ?></h3>
                        <p><strong><?php echo 54 ?> €</strong></p>
                    </div>
                </div>

            </div>

            <!-- Paginación del Carrusel -->
            <div class="swiper-pagination"></div>

            <!-- Botones de navegación del Carrusel -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
    </div>
    

</div>

<?php
    }
?>


<!-- Reseñas del Juego -->

<div class="reseñas_juego">
    <a href="/TDG/juegos/view/review?id=<?php echo $juego["id"] ?>"><h2 class="enlace">Reseñas</h2></a>

    <div>
        
    </div>
</div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="/TDG/public/JS/carrusel.js"></script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>