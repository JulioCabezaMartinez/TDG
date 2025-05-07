<?php

$css = 'main';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';

?>

<div class="landing">
    <h1>To Do Games</h1>
    <p>Plataforma gamer donde puedes gestionar tus juegos con listas de deseados, pendientes, completados y en juego, además de comprar y vender títulos físicos con otros usuarios de forma segura.</p>
    <div>
        
    </div>
</div>

<div class="recien_añadidos">
    <h2>Recien añadidos</h2>
    <div class="recien_añadidos__container">
        <?php
        foreach ($mas_vendidos as $recien_añadido) {
        ?>
            <div class="recien_añadidos__item">
                <img src="<?= $recien_añadido['Imagen'] ?>" alt="<?= $recien_añadido['Nombre'] ?>">
                <h3><?= $recien_añadido['Nombre'] ?></h3>
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

                    <a href=""></a> <!-- Se llena este enlace va a ser todo el div con el id del juego enviado con $_GET -->
                    <div class="card-item swiper-slide">
                        <img src="<?php echo $juego['Imagen']; ?>" alt="">
                        <div class="card-content">
                            <h3><?php echo $juego['Nombre'] ?></h3>
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
<?php
if($_SESSION["id_usuario"]){
?>
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
                                <img src="<?php echo $juego['Imagen']; ?>" alt="">
                                <div class="card-content">
                                    <h3><?php echo $juego['Nombre'] ?></h3>
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
<?php
}
?>


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
<script src="/TDG/public/JS/carrusel.js"></script>

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