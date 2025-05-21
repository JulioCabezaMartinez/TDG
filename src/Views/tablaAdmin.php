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
                <h5 class="modal-title" id="exampleModalLongTitle">Modificación <?php echo $entidad ?></h5>
            </div>
            <div class="modal-body">
                <?php
                foreach ($columnas as $columna) {
                ?>
                    <div>

                        <?php
                        if ($columna == "Descripcion") {
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                            <textarea class="form-control" id="<?php echo $columna ?>Input" rows="3"></textarea>
                        <?php
                        } elseif ($columna == "id") {
                        ?>
                            <input type="hidden" id="<?php echo $columna ?>Input" value="">
                        <?php
                        } elseif ($columna == "Imagen") {
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?> (URL):</strong></label>
                            <input type="text" class="form-control" id="<?php echo $columna ?>Input">

                        <?php
                        } elseif ($columna == "Password") {
                        ?>
                        <?php
                        } elseif ($columna == "Anyo_salida") {
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong>Año de Salida:</strong></label>
                            <input type="date" class="form-control" id="<?php echo $columna ?>Input">

                        <?php
                        } else {
                        ?>
                            <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                            <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                        <?php
                        }
                        ?>

                    </div>
                    <br>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn_modificar" type="button" class="btn btn-primary" data-dismiss="modal">Modificar Dato</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<div class="content">
    <input type="hidden" id="entidad" name="entidad" value="<?php echo $entidad ?>">
    <h2 class="h2-entidad">Tabla <?php echo $entidad ?></h2>
    <br><br>
    <a class="enlace-vuelta enlace" href="/TDG/panelAdmin">< Volver a Panel de Administrador</a>
    <div class="paginacion"></div>
    <table id="tabla-datos" class="table table-striped table-dark tabla-datos"></table>

    <div id="tabla-movil" class="tabla-movil"></div> 
</div>

<script>
    window.addEventListener('resize', updateContentMargin);

    function updateContentMargin() {
        const sidebarWidth = document.querySelector('.barra-lateral').offsetWidth;
        document.querySelector('.content').style.marginLeft = `${sidebarWidth}px`;
    }

    //Llamar la función al cargar la página para asegurarse de que se ajuste desde el principio
    updateContentMargin();
</script>

<script src="/TDG/public/JS/tablaAdmin.js"></script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>