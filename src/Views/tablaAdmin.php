<?php

if(empty($_SESSION) || !$_SESSION["Admin"]) {
    header("Location: /TDG/");
    exit();
}

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
                switch ($entidad) {
                    case "reviews":
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
                                    case "id_Escritor":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Escritor:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un usuario</option>
                                    <?php
                                    foreach ($usuarios as $usuario) {
                                    ?>
                                        <option value="<?php echo $usuario['id'] ?>"><?php echo $usuario['Nombre'] . ' ' . $usuario['Apellido'] . ' | ' . $usuario['Correo']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            <?php
                                    break;
                                case "id_Juego":
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
                        break;
                    case "juegos":
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
                                case "Descripcion":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <textarea class="form-control" id="<?php echo $columna ?>Input" rows="3"></textarea>
                                <br>
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
                                case "calificacion":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Calificación:</strong></label>
                                <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0" max="5" step="0.1">
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
                
                        break;
                    case "usuarios":
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
                                case "Admin":
                            ?>
                                <label class="form-check-label" for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <input type="checkbox" class="form-check-input" id="<?php echo $columna ?>Input" name="<?php echo $columna ?>Input">
                                <br><br>
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
                        break;
                    case "productos":
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
                                        <option value="<?php echo $consola['id'] ?>"><?php echo $consola['Nombre']?></option>
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
                                <label for="<?php echo $columna ?>Label"><strong>Vendedor:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un usuario</option>
                                    <?php
                                    foreach ($usuarios as $usuario) {
                                    ?>
                                        <option value="<?php echo $usuario['id'] ?>"><?php echo $usuario['Nombre'] . ' ' . $usuario['Apellido'] . ' | ' . $usuario['Correo']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
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
                        break;
                    default:
                        
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