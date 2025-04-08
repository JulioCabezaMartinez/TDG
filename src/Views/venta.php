<?php
$juego = ["name" => "Juego 1", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "precio" => 59.99, "estado" => "Usado", "Modelo" => "Playstation 3", "genero" => ["Acción", "Aventura"]];
$css = 'venta';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h1><?php echo $juego['name'] ?></h1>

<img src="<?php echo $juego['background_image'] ?>" alt="">

<h2><?php echo $juego['precio'] . " €" ?></h2>

<hr>

<div>
    <h3>Caracteristicas del Producto</h3>
    <div>
        <div class="info_juego">
            <div>
                <p><strong>Estado: </strong><?php echo $juego['estado'] ?></p>
            </div>
            <div>
                <p><strong>Modelo: </strong><?php echo $juego['Modelo'] ?></p>
            </div>
            <div>
                <h4>Géneros:</h4>
                <div>
                    <?php
                    foreach ($juego['genero'] as $genero) {
                        echo "<p>$genero</p>";
                    }
                    ?>
                </div>
            </div>

        </div>
        <hr>
        <div class="descripcion_juego">
            <h2>Descripción</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatibus.</p>
        </div>
    </div>

</div>

<!-- Botón de compra -->
<button id="btn_comprar">
    <p>Añadir al carrito</p>
    <i class='fa-solid fa-cart-shopping'></i>
</button>

<?php
require_once __DIR__ . '\Templates\final.php';
?>