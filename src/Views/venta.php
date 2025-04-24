<?php
require_once '../../vendor/autoload.php'; // Autoload de Composer para pruebas. En la version debe de ir en el Index.php.

use App\Models\Juego;
use App\Models\Genero;
$id_juego = 28; // ID del juego a mostrar

$juego = new Juego();
$genero = new Genero();

$juego = $juego->getById($id_juego);
$generos = $genero->getGenerosJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar.

$css = 'venta';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h1 class="nombre_juego"><?php echo $juego['Nombre'] ?></h1>

<img src="<?php echo $juego['Imagen'] ?>" alt="">

<h2><?php echo 34 . " €" ?></h2>

<hr>

<div>
    <h3>Caracteristicas del Producto</h3>
    <div>
        <div class="info_juego">
            <div>
                <p><strong>Estado: </strong><?php echo "Nuevo" ?></p>
            </div>
            <div>
                <p><strong>Modelo: </strong><?php echo "PS3" ?></p>
            </div>
            <div>
                <h4>Géneros:</h4>
                <div>
                    <?php
                    foreach ($generos as $genero) {
                    ?>
                        <p><?php echo $genero['Nombre'] ?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>
        <hr>
        <div class="descripcion_juego">
            <h2>Descripción</h2>
            <?php echo $juego['Descripcion'] ?>
        </div>
    </div>

</div>

<!-- Botón de compra -->
<button id="btn_comprar">
    <p>Comprar</p>
    <i class='fa-solid fa-cart-shopping'></i>
</button>

<?php
require_once __DIR__ . '\Templates\final.php';
?>