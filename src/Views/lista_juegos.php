<?php
$juegos=[["name"=>"Juego 1","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","rating"=>4.5,"released"=>"2023-01-01","platforms"=>"PC, PS5"],
["name"=>"Juego 2","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","rating"=>4.0,"released"=>"2023-02-01","platforms"=>"Xbox, Switch"],
["name"=>"Juego 3","background_image"=>"https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg","rating"=>3.5,"released"=>"2023-03-01","platforms"=>"PC, PS4"]];
$css = 'lista_juegos';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 style="text-align: center;">Juegos</h3>

<div>
    <button class="btn_redondo">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>
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

<?php
require_once __DIR__ . '\Templates\final.php';
?>