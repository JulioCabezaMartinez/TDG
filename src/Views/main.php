<?php

$css = 'main';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';

?>

<div class="landing">
        <h4>To Do Games</h>
        <h1>LA MEJOR MANERA DE ORGANIZAR TUS JUEGOS</h1>
        <p>Gestiona tus juegos en listas personalizadas, además de comprar y vender los juegos que no usas de forma segura.</p>

        <a href="/TDG/juegos">
            <p class="boton-perso">Ver Juegos</p>
        </a>
</div>
<div class="recien_añadidos">

    <a class="enlace-main" href="/TDG/juegos">
        <i class="fa-solid fa-gamepad"></i>
        <h2 class="enlace">Recién añadidos</h2>
    </a>
    <div class="recien_añadidos__container">
        <?php
        foreach ($ultimos_juegos as $recien_añadido) {
        ?>
            <div class="recien_añadidos__item">
                <a href="/TDG/juegos/view?id=<?php echo $recien_añadido["id"] ?>">
                    <img src="<?= $recien_añadido['Imagen'] ?>" alt="<?= $recien_añadido['Nombre'] ?>">
                    <?php
                    if(strlen($recien_añadido['Nombre'])>20){
                    ?>
                        <h3 class="titulo_juego" data-prueba="adios"><?php echo substr($recien_añadido['Nombre'], 0, 20) . "..." ?></h3>
                    <?php
                    }else{
                    ?>
                        <h3 class="titulo_juego" data-prueba="hola"><?php echo $recien_añadido['Nombre'] ?></h3>
                    <?php
                    }
                    ?>
                </a>
            </div>
        <?php
        }
        ?>
        <!-- Para evitar el salto del carrusel al llegar al final se duplica el contenido para que se consiga el loop infinito -->
        <?php
        foreach ($ultimos_juegos as $recien_añadido) {
        ?>
            <div class="recien_añadidos__item">
                <a href="/TDG/juegos/view?id=<?php echo $recien_añadido["id"] ?>">
                    <img src="<?= $recien_añadido['Imagen'] ?>" alt="<?= $recien_añadido['Nombre'] ?>">
                    <?php
                    if(strlen($recien_añadido['Nombre'])>20){
                    ?>
                        <h3 class="titulo_juego" data-prueba="adios"><?php echo substr($recien_añadido['Nombre'], 0, 20) . "..." ?></h3>
                    <?php
                    }else{
                    ?>
                        <h3 class="titulo_juego" data-prueba="hola"><?php echo $recien_añadido['Nombre'] ?></h3>
                    <?php
                    }
                    ?>

                </a>
            </div>
        <?php
        }
        ?>
    </div>

</div>

<div class="ultimos_productos">
    <a class="enlace-main" href="/TDG/ventas">
        <i class="fa-solid fa-store"></i>
        <h2>Últimos Productos</h2>
    </a>
    
    <div class="swiper">
        <div class="card-wrapper">
            <div class="card-list swiper-wrapper">

                <?php
                foreach ($mas_vendidos as $venta) {
                ?>

                    <a href=""></a> <!-- Se llena este enlace va a ser todo el div con el id del juego enviado con $_GET -->
                    <div class="card-item swiper-slide">
                        <img src="/TDG/public/IMG/<?php echo $venta['img_venta']; ?>" alt="">
                        <div class="card-content">
                            <h3><?php echo $venta['Titulo'] ?></h3>
                            <br>
                            <h5><?php echo $venta['Precio'] ?> €</h5>
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
if (isset($_SESSION["id_usuario"])) {
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


<div class="lista_reviews">
    <h2>Ultimas Reviews</h2>
    <?php
    foreach ($lista_reviews as $review) {
        $contenido_reducido = str_split($review['Contenido'], 10)[0];
        $contenido_reducido .= " ...";
    ?>

        <div class="review">
            <div class="cabecera_review">
                <img src="/TDG/public/IMG/Users-img/<?php echo $usuario["Imagen"] ?>" alt=""> <!-- Imagen de usuario del Usuario asociado a la review -->
                <h3><?php echo $review["juego"] ?></h3> <!-- Nombre del Usuario que ha escrito la reseña -->
            </div>

            <?php
            if (strlen($review['Contenido']) >= 10) {
            ?>
                <p id="texto_reducido" class="review_texto"><?php echo $contenido_reducido; ?></p>
                <p class="review_texto d-none"><?php echo $review['Contenido']; ?></p>
            <?php
            } else {
            ?>
                <p id="texto_reducido" class="review_texto d-none"><?php echo $contenido_reducido; ?></p>
                <p class="review_texto"><?php echo $review['Contenido']; ?></p> <!-- Texto completo de la Review -->
            <?php
            }
            ?>
            <div class="review_footer">
                <div class="review_ver_mas_container boton-perso">
                    <i class="fa-solid fa-arrow-down"></i>
                    <p class="review_ver_mas">Ver más</p>
                </div>
            </div>
        </div>

    <?php
    }
    ?>
</div>

<?php
include_once __DIR__ . "./Templates/footer.php";
?>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="/TDG/public/JS/carrusel.js"></script>

<!-- Archivo JS de Main -->
<script type="module" src="/TDG/public/JS/main.js"></script>
<?php
require_once __DIR__ . '\Templates\final.php';
?>