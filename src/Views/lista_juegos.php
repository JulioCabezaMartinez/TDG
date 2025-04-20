<?php
$juegos=[["name"=>"Juego 1","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","rating"=>4.5,"released"=>"2023-01-01","platforms"=>"PC, PS5"],
["name"=>"Juego 2","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","rating"=>4.0,"released"=>"2023-02-01","platforms"=>"Xbox, Switch"],
["name"=>"Juego 3","background_image"=>"https://media.rawg.io/media/games/511/5118aff5091cb3efec399c808f8c598f.jpg","rating"=>3.5,"released"=>"2023-03-01","platforms"=>"PC, PS4"]];
$css = 'lista_juegos';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 style="text-align: center;">Juegos</h3>

<div class="filtros_desplegable">
    <div class="filtros_texto">
        <p>Filtros</p>
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
    </div>

    <div class="filtros_opciones">
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion1" name="opcion1" value="opcion1">
            <label for="opcion1">Opción 1</label>
        </div>
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion2" name="opcion2" value="opcion2">
            <label for="opcion2">Opción 2</label>
        </div>
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion3" name="opcion3" value="opcion3">
            <label for="opcion3">Opción 3</label>
        </div>
    </div>
</div>

<div id="filtros">
    <button id="boton_filtro" class="btn_filtros">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>

    <!-- <hr> Poner una barra en vertical para separar el boton de los filtros -->

    <!-- Carrusel de Filtros activos -->
    <div class="swiper">
        <div class="card-wrapper">
            <div class="filtros_activos card-list swiper-wrapper">
                
            </div>
        </div>
    </div>
    
</div>

<div class="list_juegos">
    
    <?php
    foreach ($juegos as $juego) {
        echo "<div class='juego'>
                <img src='{$juego['background_image']}' alt=''>
                <div class='info_juego'>
                    <h1>{$juego['name']}</h1>
                    <p>{$juego['rating']}</p>
                    <p>{$juego['released']}</p>
                    <p>{$juego['platforms']}</p>
                    <div class='btn_listas'>
                        <i class='fa-regular fa-circle-play'></i>
                        <i class='fa-solid fa-clock'></i>
                        <i class='fa-solid fa-check'></i>
                        <i class='fa-regular fa-heart'></i>
                    </div>
                </div>
            </div>";
    }
    ?>
</div>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="carrusel.js"></script>

<script>
    $("#boton_filtro").click(function() {
        console.log("click");
        $(".filtros_desplegable").toggleClass("active");
    });

    $(".opcion_filtro").click(function() {
        $(".filtros_activos").append("<div class='card-item swiper-slide'> <i class='fa-solid fa-xmark eliminar-filtro'></i>"+ $(this).val() + "</div>");
    
        numeroDeSlides = $(".filtros_activos").children().length; // Obtiene el número de slides actuales.

        if (window.swiper) {
            swiper.update(); // Actualiza el swiper para que reconozca los nuevos elementos añadidos.
        } 
    });

    $(document).on("click", ".eliminar-filtro", function () {
    // Eliminamos el slide padre (el div .swiper-slide)
    $(this).closest(".swiper-slide").remove();

    // Actualizamos Swiper para que se entere del cambio
    if(window.swiper) {
        swiper.update();
    }
});


    
</script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>