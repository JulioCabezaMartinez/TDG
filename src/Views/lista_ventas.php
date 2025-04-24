<?php
$juegos=[["name"=>"Juego 1","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>4.5],
["name"=>"Juego 2","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>4.0],
["name"=>"Juego 3","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>3.5],
["name"=>"Juego 4","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>3.0],
["name"=>"Juego 5","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>2.5],
["name"=>"Juego 6","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>2.0],
["name"=>"Juego 7","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>1.5],
["name"=>"Juego 8","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>1.0],
["name"=>"Juego 9","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>0.5],
["name"=>"Juego 10","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","precio"=>0.0]];
$css = 'lista_ventas';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 style="text-align: center;">Ventas</h3>

<div class="filtros_desplegable">
    <div class="filtros_texto">
        <p>Filtros</p>
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
    </div>

    <div class="filtros_opciones">
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion1" name="opcion1" value="opcion1">
            <label for="opcion1">Opción 1</label>
        </div>
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion2" name="opcion2" value="opcion2">
            <label for="opcion2">Opción 2</label>
        </div>
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion3" name="opcion3" value="opcion3">
            <label for="opcion3">Opción 3</label>
        </div>
    </div>
</div>

<div>
    <button id="boton_filtro" class="btn_redondo">
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
                </div>
            </div>";
    }
    ?>
</div>

<script>
    $("#boton_filtro").click(function() {
        console.log("click");
        $(".filtros_desplegable").toggleClass("active");
    });
</script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>