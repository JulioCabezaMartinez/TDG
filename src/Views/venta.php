<?php
$css = 'venta';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<div class="principal_venta">
    <h1 class="nombre_venta"><?php echo $venta['Titulo'] ?></h1>

    <img class="img_venta" src="/TDG/public/IMG/<?php echo $venta["img_venta"] ?>" alt="">

    <h2 class="precio_venta"><?php echo $venta["Precio"] . " €" ?></h2>
</div>


<hr>

<div class="caracteristicas_producto">
    <h3>Caracteristicas del Producto</h3>
    <div>
        <div class="info_venta">
            <div>
                <p><strong>Estado: </strong><?php echo "Nuevo" ?></p>
            </div>
            <div>
                <p><strong>Modelo: </strong><?php echo "PS3" ?></p>
            </div>
            <div>
                <h4>Géneros:</h4>
                <div class="generos_juego">
                    <?php
                    foreach ($generos as $genero) {
                    ?>
                        <p class="genero"><?php echo $genero['Nombre'] ?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>
        <hr>
        <div class="descripcion_juego">
            <h2>Descripción del Juego</h2>
            <?php echo $juego['Descripcion'] ?> <!-- Descripción del juego que se está vendiendo -->
        </div>
    </div>

</div>

<!-- Botón de compra -->
 <a href="/TDG/ventas/view/checkout?id=<?php echo $venta["id"] ?>">
    <button id="btn_comprar">
        <p>Comprar</p>
        <i class='fa-solid fa-cart-shopping'></i>
    </button>
 </a>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>