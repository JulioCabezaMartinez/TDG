<?php
$juego = ["name" => "Juego 1", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "rating" => 4.5, "released" => "2023-01-01", "platforms" => ["PC", "PS5"], "genres" => ["Acción", "Aventura"], "price" => 59.99];
$css = 'juego';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h1><?php echo $juego['name'] ?></h1>

<!-- <img src="<?php echo $juego['background_image'] ?>" alt=""> -->

<div class='btn_listas'>

    <div><i class='fa-regular fa-circle-play'></i></div>
    <div>
        <div><i class='fa-solid fa-clock'></i></div>
        <div><i class='fa-solid fa-check'></i></div>
        <div><i class='fa-regular fa-heart'></i></div>
    </div>

</div>

<div>
    <div class="info_juego">

        <div>
            <h3>Géneros</h3>
            <div>
                <?php
                    foreach ($juego['genres'] as $genero) {
                        echo "<p>$genero</p>";
                    }
                ?>
            </div>
        </div>
        <div>
            <h3>Plataformas</h3>
            <div>
                <?php
                    foreach ($juego['platforms'] as $plataforma) {
                        echo "<p>$plataforma</p>";
                    }
                ?>
            </div>
        </div>
        <div>
            <h3>Año de Salida</h3>
            <p><?php echo $juego['released'] ?></p>
        </div>

    </div>
    <div class="descripcion_juego">
        <h2>Descripción</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatibus.</p>
    </div>

</div>

<!-- Carrusel de ventas del Juego -->

<div class="ventas_juego">
    <h2>Ventas</h2>
    <a href="/">Ventas de <?php echo $juego['name'] ?></a>

    <div class="swiper">
        <div class="card-wrapper">
            <div class="card-list swiper-wrapper">

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego["background_image"]; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['name'] ?></h3>
                        <p><strong><?php echo $juego['price'] ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego["background_image"]; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['name'] ?></h3>
                        <p><strong><?php echo $juego['price'] ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego["background_image"]; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['name'] ?></h3>
                        <p><strong><?php echo $juego['price'] ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego["background_image"]; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['name'] ?></h3>
                        <p><strong><?php echo $juego['price'] ?> €</strong></p>
                    </div>
                </div>

                <div class="card-item swiper-slide">
                    <img src="<?php echo $juego["background_image"]; ?>" alt="">
                    <div class="card-content">
                        <h3><?php echo $juego['name'] ?></h3>
                        <p><strong><?php echo $juego['price'] ?> €</strong></p>
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


<!-- Reseñas del Juego -->

<div class="reseñas_juego">
    <h2>Reseñas</h2>
    <a href="/">Reseñas de <?php echo $juego['name'] ?></a>

    <div>
        
    </div>
</div>


<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="carrusel.js"></script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>