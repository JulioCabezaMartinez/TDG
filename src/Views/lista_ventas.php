<?php
$css = 'lista_ventas';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';

if (!empty($_SESSION)) {
?>
    <!-- Modal de creación de Venta -->
    <div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="creacion-dato-header">Creación Producto</h5>
                </div>
                <div class="modal-body">

                    <?php
                    foreach ($columnas as $columna) {
                    ?>
                        <div>
                            <?php
                            switch ($columna) {
                                case "id":
                                    // No mostrar el campo id en el formulario de creación
                                    break;
                                case "Titulo":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>*Title:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Estado":
                            ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*State:</strong></label>
                                    <select class="form-select" id="<?php echo $columna ?>Input">
                                        <option value="" selected disabled>Select an state</option>
                                        <option value="Nuevo">New</option>
                                        <option value="2º Mano">2nd Hand</option>
                                        <option value="Reparado">Repaired</option>
                                    </select>
                                    <br>
                                <?php
                                    break;
                                case "Consola":
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*Platform:</strong></label>
                                    <select class="form-select" id="<?php echo $columna ?>Input">
                                        <option value="" selected disabled>Select a platform</option>
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
                                    <label for="<?php echo $columna ?>Label"><strong>*Price:</strong></label>
                                    <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0" step="10">
                                    <br>
                                <?php
                                    break;
                                case "Estado_Venta":
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*Sale state:</strong></label>
                                    <select class="form-select" id="<?php echo $columna ?>Input">
                                        <option value="" selected disabled>Select an sale state</option>
                                        <option value="Disponible">Available</option>
                                        <option value="Sin Stock">Out of Stock</option>

                                    </select>
                                    <br>
                                <?php
                                    break;
                                case "Stock":
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*Stock:</strong></label>
                                    <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0">
                                    <br>
                                <?php
                                    break;
                                case "img_venta":
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>Image:</strong></label>
                                    <input type="file" class="form-control" name="<?php echo $columna ?>" id="<?php echo $columna ?>Input">
                                    <br>
                                <?php
                                    break;
                                case "id_juego":
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*Game:</strong></label>
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

                                case "id_Vendedor":
                                ?>

                                <?php
                                    break;
                                default:
                                ?>
                                    <label for="<?php echo $columna ?>Label"><strong>*<?php echo $columna ?>:</strong></label>
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
                    <p class="error" id="error_global"></p>
                </div>
                <div class="modal-footer">
                    <button id="btn_cerrar_modal" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancelar</button>
                    <button id="btn_crear" type="button" class="boton-perso">Crear Producto</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de creación de Venta -->
<?php
}
?>
<h3 class="mt-4" style="text-align: center;">SHOP</h3>

<?php
if (!empty($_SESSION)) {
?>
    <button id="btn_crear_producto" class="boton-perso">Upload Product</button>
    <br><br>
<?php
}
?>

<div id="filtros">
    <button id="boton_filtro" class="btn_filtros boton-perso">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filters</p>
    </button>

    <!-- <hr> Poner una barra en vertical para separar el boton de los filtros -->

    <!-- Carrusel de Filtros activos
    <div class="swiper">
        <div class="card-wrapper">
            <div class="filtros_activos card-list swiper-wrapper">

            </div>
        </div>
    </div> -->

</div>

<!-- Menu de Filtros -->
<div class="filtros_desplegable">
    <div class="filtros_opciones">
        <div>
            <label for="nombre">Game's Name:</label>
            <input class="form-control" id="nombreInputFiltro" type="text">
        </div>
        <br>
        <!-- Se puede poner año de salida con este SQL: WHERE YEAR(Anyo_salida) = ?; -->
        <div>
            <label for="">Max Price: </label>
            <br>
            <input class="form-control w-50" id="precioMaxInputFiltro" type="number">
        </div>
        <br>
        <div>
            <label for="">Min Price: </label>
            <br>
            <input class="form-control w-50" id="precioMinInputFiltro" type="number">
        </div>
        <br>
        <div>
            <label for="">Platform: </label>
            <br>
            <select class="form-select" id="ConsolaInputFiltro">
                <option value="" selected>Select a platform</option>
                <?php
                foreach ($consolas as $plataforma) {
                ?>
                    <option value="<?php echo $plataforma["id"] ?>"><?php echo $plataforma["Nombre"] ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <br>
        <div>
            <label for="">State: </label>
            <br>
            <select class="form-select" id="EstadoInputFiltro">
                <option value="" selected>Select a state</option>
                <option value="Nuevo">Nuevo</option>
                <option value="2º Mano">2º Mano</option>
                <option value="Reparado">Reparado</option>
            </select>
        </div>
        <br>
        <p id="resetFiltros" class="enlace">Reset filters</p>
        <br>
        <button id="aplicarFiltros" class="boton-perso w-25">Filter</button>

    </div>
</div>
<!-- Menu de Filtros -->


<div class="paginacion"></div>

<div class="centrar-div">
    <div id="lista_ventas">

    </div>
</div>

<div class="paginacion"></div>

<?php
include_once __DIR__ . "/Templates/footer.php";
?>

<script src="/public/JS/lista_ventas.js"></script>

<?php
require_once __DIR__ . '/Templates/final.php';
?>