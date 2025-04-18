<?php
$juegos=[["name"=>"Juego 1","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>4.5],
["name"=>"Juego 2","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>4.0],
["name"=>"Juego 3","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>3.5]];
$css = 'lista_ventas';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 style="text-align: center;">Ventas</h3>

<div>
    <button class="btn_redondo">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>

    <!-- Carrusel de Filtros activos -->
    <div class="swiper">
        <div class="card-wrapper">
            <div class="card-list swiper-wrapper">

                <div class="card-item swiper-slide">
                    
                </div>

                <div class="card-item swiper-slide">
                    
                </div>

                <div class="card-item swiper-slide">
                    
                </div>

                <div class="card-item swiper-slide">
                    
                </div>

                <div class="card-item swiper-slide">
                    
                </div>

            </div>

        </div>
    </div>
</div>

<div class="list_ventas">
    
    <?php
    foreach ($juegos as $juego) {
        echo "<div class='juego'>
                <img src='{$juego['background_image']}' alt=''>
                <div class='info_juego'>
                    <h1>{$juego['name']}</h1>
                    <p class='precio'>{$juego['precio']} €</p>
                    <div class='btn_listas'>
                        <div>
                            <p>Añadir al carrito</p><i class='fa-solid fa-cart-shopping'></i>
                        </div>
                    </div>
                </div>
            </div>";
    }
    ?>
</div>

<?php
require_once __DIR__ . '\Templates\final.php';
?>