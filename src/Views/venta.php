<?php
$css = 'venta';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<!-- Modal de modificación de Producto -->
<div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Modificación Producto</h5>
            </div>
            <div class="modal-body">
                <?php
                foreach ($columnas as $columna) {
                ?>
                    <div>
                        <?php
                        switch ($columna) {
                            case "id":
                        ?>
                                <input type="hidden" id="<?php echo $columna ?>Input" value="">
                            <?php
                                break;
                            case "Estado":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un estado</option>
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="2º Mano">2º Mano</option>
                                    <option value="Reparado">Reparado</option>
                                </select>
                                <br>
                            <?php
                                break;
                            case "Consola":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Consola:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione una consola</option>
                                    <?php
                                    foreach ($consolas as $consola) {
                                    ?>
                                        <option value="<?php echo $consola['id'] ?>"><?php echo $consola['Nombre'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            <?php
                                break;
                            case "Precio":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0" step="10">
                                <br>
                            <?php
                                break;
                            case "Estado_Venta":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Estado de Venta:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un estado de venta</option>
                                    <option value="Disponible">Disponible</option>
                                    <option value="Sin Stock">Sin Stock</option>

                                </select>
                                <br>
                            <?php
                                break;
                            case "Stock":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Stock:</strong></label>
                                <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0">
                                <br>
                            <?php
                                break;
                            case "img_venta":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Imagen:</strong></label>
                                <input type="file" class="form-control" name="<?php echo $columna ?>" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                break;
                            case "id_Vendedor":
                            ?>
                                <!-- No quiero que se pueda cambiar el id del Vendedor -->
                            <?php
                                break;
                            case "id_juego":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Juego:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un juego</option>
                                    <?php
                                    foreach ($juegos as $juego) {
                                    ?>
                                        <option value="<?php echo $juego['id'] ?>"><?php echo $juego['Nombre'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            <?php
                                break;
                            default:
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                        <?php
                                break;
                        }
                        ?>
                    </div>
                <?php
                }

                ?>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancelar</button>
                <button id="btn_modificar" type="button" class="boton-perso" data-dismiss="modal">Modificar Producto</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<div class="principal_venta">

    <input type="hidden" name="id_producto" id="id_producto" value="<?php echo $venta['id'] ?>">
    <h1 class="nombre_venta"><?php echo $venta['Titulo'] ?></h1>

    <?php
    if (!empty($_SESSION) && (($venta["id_Vendedor"] == $_SESSION['usuarioActivo'] && ($venta["Stock"] > 0)) || $_SESSION['Admin'])) {
    ?>
        <div class="botones_Producto">
            <?php
            if (!empty($_SESSION) && $_SESSION['Admin']) {
            ?>
                <button id="btn-quitarBD-venta" class="boton-perso">Delete from the Database</button>
            <?php
            }
            ?>
            <button id="btn-quitar-venta" class="boton-perso">Remove from store</button>
            <button id="btn-modificar-venta" class="boton-perso boton-perso-secundario">Modify Product</button>
        </div>
        <br>
    <?php
    }
    ?>

    <img class="img_venta" src="/public/IMG/Productos-img/<?php echo $venta["img_venta"] ?>" alt="">

    <h2 class="precio_venta"><?php echo $venta["Precio"] . " €" ?></h2>

</div>


<hr>

<div class="caracteristicas_producto">
    <div class="info_venta">
        <h3>Product Features</h3>
        <div>
            <p><strong>State: </strong><?php echo "Nuevo" ?></p>
        </div>
        <div>
            <p><strong>Model: </strong><?php echo "PS3" ?></p>
        </div>
        <div>
            <h4>Genres:</h4>
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
        <h2>Game Description</h2>
        <p><?php echo $juego['Descripcion'] ?></p> <!-- Descripción del juego que se está vendiendo -->
    </div>
</div>

<?php
if (!empty($_SESSION) && $venta["Stock"] > 0 && $venta["id_Vendedor"] != $_SESSION['usuarioActivo']) {
    // Si el usuario está logueado y el producto está disponible para la compra
    // Mostrar botón de compra
?>
    <!-- Botón de compra -->
    <a href="/ventas/view/checkout?id=<?php echo $venta["id"] ?>">
        <button id="btn_comprar" class="boton-perso">
            <p>Buy</p>
            <i class='fa-solid fa-cart-shopping'></i>
        </button>
    </a>
<?php
}

?>


<script src="/public/JS/venta.js"></script>

<?php
include_once __DIR__ . "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>