<?php
$css = 'tablaAdmin';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\barra-lateral.admin.php';
?>

<!-- Modal de creación de Review -->
<div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Modificación <?php echo $entidad ?></h5>
                <h5 class="modal-title" id="creacion-dato-header">Creación <?php echo $entidad ?></h5>
            </div>
            <div class="modal-body">
                <?php
                foreach ($columnas as $columna) {
                ?>
                    <div>

                        <?php
                        switch ($columna) {
                            case "Descripcion":
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                            <textarea class="form-control" id="<?php echo $columna ?>Input" rows="3"></textarea>
                            <br>
                        <?php
                                break;
                            case "id":
                        ?>
                            <input type="hidden" id="<?php echo $columna ?>Input" value="">
                        <?php
                                break;
                            case "Admin":
                        ?>
                            <label class="form-check-label" for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                            <input type="checkbox" class="form-check-input" id="<?php echo $columna ?>Input" name="<?php echo $columna ?>Input">
                            <br><br>
                        <?php
                                break;
                            case "Imagen":
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?> (URL):</strong></label>
                            <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                            <br>
                        <?php
                                break;
                            case "Anyo_salida":
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong>Año de Salida:</strong></label>
                            <input type="date" class="form-control" id="<?php echo $columna ?>Input">
                            <br>
                        <?php
                                break;
                            case "Premium":
                        ?>
                            <label class="form-check-label" for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                            <input type="checkbox" class="form-check-input" id="<?php echo $columna ?>Input" name="<?php echo $columna ?>Input">
                            <br><br>
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
                <button id="btn_crear" type="button" class="boton-perso" data-dismiss="modal">Crear Dato</button>
                <button id="btn_modificar" type="button" class="boton-perso" data-dismiss="modal">Modificar Dato</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<div class="content">
    <input type="hidden" id="entidad" name="entidad" value="<?php echo $entidad ?>">
    <h2 class="h2-entidad ms-3">Tabla <?php echo $entidad ?></h2>
    <br><br>
    <a class="enlace-vuelta enlace" href="/TDG/panelAdmin">< Volver a Panel de Administrador</a>
    
    <button id="btn_crear_dato" class="boton-perso ms-3">Crear <?php echo $entidad ?></button>
    <br><br>

    <div class="paginacion"></div>
    <table id="tabla-datos" class="table table-striped table-dark tabla-datos"></table>

    <div id="tabla-movil" class="tabla-movil"></div> 
</div>

<script src="/TDG/public/JS/tablaAdmin.js"></script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>