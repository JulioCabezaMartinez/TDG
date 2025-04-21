<?php
$mas_vendidos = [
    ["name" => "Juego 1", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "rating" => 4.5, "released" => "2023-01-01", "platforms" => "PC, PS5"],
    ["name" => "Juego 2", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "rating" => 4.0, "released" => "2023-02-01", "platforms" => "Xbox, Switch"],
    ["name" => "Juego 3", "background_image" => "https://media.rawg.io/media/games/511/5118aff5091cb3efec399c808f8c598f.jpg", "rating" => 3.5, "released" => "2023-03-01", "platforms" => "PC, PS4"]
];
$reviews_populares = [
    ["user_img" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "game_name" => "Juego 1", "review" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis incidunt vero ipsam magni eligendi, enim nisi quo officia, quia autem sunt consectetur optio laborum eius a magnam veniam, animi quisquam.
Non odio optio minima pariatur quae nulla impedit iusto facere est, animi cumque illo quam aliquam magni omnis. Provident facere culpa rem totam! Laboriosam, autem. Ipsum inventore explicabo distinctio quis?
Optio aut laborum non similique sit nisi id earum? Necessitatibus amet explicabo eum? Eum delectus, enim, est aspernatur provident perferendis sint architecto eos atque ipsam accusamus laboriosam, nostrum eveniet excepturi.
Ut distinctio atque nisi nihil dignissimos consequuntur hic dolorem corrupti? Est consectetur, ipsa impedit recusandae id veritatis vitae consequuntur qui modi, minus distinctio harum nostrum error inventore quae incidunt culpa?", "rating" => 5],
    ["user_img" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "game_name" => "Juego 2", "review" => "Muy divertido.", "rating" => 4],
    ["user_img" => "https://media.rawg.io/media/games/511/5118aff5091cb3efec399c808f8c598f.jpg", "game_name" => "Juego 3", "review" => "No está mal.", "rating" => 3]
];
$css = 'main';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<div class="recien_añadidos">
    <h2>Recien añadidos</h2>
    <div class="recien_añadidos__container">
        <?php
        foreach ($mas_vendidos as $recien_añadido) {
        ?>
            <div class="recien_añadidos__item">
                <img src="<?= $recien_añadido['background_image'] ?>" alt="<?= $recien_añadido['name'] ?>">
                <h3><?= $recien_añadido['name'] ?></h3>
            </div>
        <?php
        }
        ?>
    </div>

</div>

<div class="mas_vendidos">
    <h2>Más vendidos</h2>
    <div class="swiper">
        <div class="card-wrapper">
            <div class="card-list swiper-wrapper">

                <?php
                foreach ($mas_vendidos as $juego) {
                ?>

                    <div class="card-item swiper-slide">
                        <img src="<?php echo $juego["background_image"]; ?>" alt="">
                        <div class="card-content">
                            <h3><?php echo $juego['name'] ?></h3>
                            <!-- Se pueden añadir más campos del juegos -->
                        </div>
                    </div>

                <?php
                }
                ?>

            </div>
            <!-- Paginación del Carrusel -->
            <div class="swiper-pagination"></div>

            <!-- Botones de navegación del Carrusel -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
    </div>
</div>

<div class="recomendados">
    <h2>Recomendados</h2>
    <div class="recomendados__container">
        <div class="swiper">
            <div class="card-wrapper">
                <div class="card-list swiper-wrapper">

                    <?php
                    foreach ($mas_vendidos as $juego) {
                    ?>

                        <div class="card-item swiper-slide">
                            <img src="<?php echo $juego["background_image"]; ?>" alt="">
                            <div class="card-content">
                                <h3><?php echo $juego['name'] ?></h3>
                                <!-- Se pueden añadir más campos del juegos -->
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>
                <!-- Paginación del Carrusel -->
                <div class="swiper-pagination"></div>

                <!-- Botones de navegación del Carrusel -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

            </div>
        </div>
    </div>

</div>

<div id="reviews_populares">

    <h2>Reviews Populares</h2>

    <div class="review">
        <?php
        foreach ($reviews_populares as $review) {
        ?>
            <div class="review_item">
                <div class="cabecera_review">
                    <img src="<?php echo $review["user_img"] ?>" alt=""> <!-- Imagen de usuario del Usuario asociado a la review -->
                    <h3><?php echo $review['game_name'] ?></h3> <!-- Nombre del Juego reseñado -->
                </div>

                <p class="review_texto" ><?php echo $review['review']; ?><!-- Texto de la reseña --></p>

                <div class="review_footer">
                    <div class="review_rating">
                        <i class="fa-light fa-thumbs-up"></i>
                        <p>
                            <?php echo $review['rating'] ?> <!-- Valoración de la reseña -->
                        </p>
                    </div>
                    
                    <div class="review_ver_mas_container">
                        <i class="fa-solid fa-arrow-down"></i>
                        <p class="review_ver_mas">Ver más</p>
                    </div>
                    
                </div>
        </div>


        <?php
        }
        ?>

    </div>

</div>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="carrusel.js"></script>

<script>
    $(document).ready(function() {
        $('.review_ver_mas').click(function() {
            const btn = document.getElementById("verMas");
            texto = $(this).parent().parent().find('.review_texto')[0];

            texto.classList.toggle("expandido");
            texto.classList.toggle("truncado");
        });
    });
</script>
<?php
require_once __DIR__ . '\Templates\final.php';
?>