<?php

$css = 'tablaAdmin';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/barra-lateral.admin.php';
?>

<!-- Modal de creación y modificación de Datos -->
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
                                    case "Contenido":
                            ?>
                                    <label for="<?php echo $columna ?>Label"><strong>Content:</strong></label>
                                    <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                    <br>
                            <?php
                                    break;
                                    case "id_Escritor":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Writter:</strong></label>
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
                                <label for="<?php echo $columna ?>Label"><strong>Game:</strong></label>
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
                                case "Nombre":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Name:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Descripcion":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Description:</strong></label>
                                <textarea class="form-control" id="<?php echo $columna ?>Input" rows="3"></textarea>
                                <br>
                            <?php
                                    break;
                                case "Imagen":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Image (URL):</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Anyo_salida":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Release Date:</strong></label>
                                <input type="date" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "calificacion":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Rate:</strong></label>
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
                                case "Nombre":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Name:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Apellido":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Surname:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Nick":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Nick:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Correo":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Email:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "Direccion":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Address:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
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
                                // No quiero que se modifique la contraseña por texto.
                                case "Password": 
                                break;
                                case "Imagen_usuario":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Image:</strong></label>
                                <input type="file" class="form-control" name="<?php echo $columna ?>" id="<?php echo $columna ?>Input">
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
                    case "post_vendidos":
                        foreach ($columnas as $columna) {
                ?>
                        <div>

                            <?php
                            switch ($columna) {
                                case "id_Post":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Product bought:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Seleccione un producto</option>
                                    <?php
                                    
                                    foreach ($productos as $producto) {
                                    ?>
                                        <option value="<?php echo $producto['id'] ?>"><?php echo $producto['Titulo'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            <?php
                                    break;
                                case "id_Comprador":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Buyer:</strong></label>
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
                                case "Fecha":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Date:</strong></label>
                                <input type="datetime-local" step="1" class="form-control" id="<?php echo $columna ?>Input">
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
                                <label for="<?php echo $columna ?>Label"><strong>*Platform:</strong></label>
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
                                <label for="<?php echo $columna ?>Label"><strong>*Price:</strong></label>
                                <input type="number" class="form-control" id="<?php echo $columna ?>Input" min="0" step="10">
                                <br>
                            <?php
                                    break;
                                case "Estado_Venta":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>*Sale state:</strong></label>
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
                                <label for="<?php echo $columna ?>Label"><strong>Image:</strong></label>
                                <input type="file" class="form-control" name="<?php echo $columna ?>" id="<?php echo $columna ?>Input">
                                <br>
                            <?php
                                    break;
                                case "id_Vendedor":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Seller:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Select an user</option>
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
                                <label for="<?php echo $columna ?>Label"><strong>Game:</strong></label>
                                <select class="form-select" id="<?php echo $columna ?>Input">
                                    <option value="" selected disabled>Select a game</option>
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
                        break;
                }
                if($entidad=="post_vendidos"){
                ?>
                <input type="hidden" id="id_PostAntiguoInput" value="">
                <input type="hidden" id="id_CompradorAntiguoInput" value="">
                <input type="hidden" id="FechaAntiguaInput" value="">
                <?php
                }
                ?>


            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancel</button>
                <button id="btn_crear" type="button" class="boton-perso" data-dismiss="modal">Add Data</button>
                <button id="btn_modificar" type="button" class="boton-perso" data-dismiss="modal">Modificate Data</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación y modificación de Datos -->

<?php
if($entidad=="usuarios"){
?>
<!-- Modal de cambio de Contraseña -->
<div class="modal fade" id="modificacionPass" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Change Password</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_pass">
                <div>
                    <label class="form-label" for="password">New Password: </label>
                    <input class="form-control" type="password" id="contraseña_cambio">
                    <br>
                    <label class="form-label" for="confirm">Confirm Password: </label>
                    <input class="form-control" type="password" id="confirm_cambio">
                    <p id="error_pass"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal_pass" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancel</button>
                <button id="btn_cambiarPass" type="button" class="boton-perso">Change Password</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de cambio de Contraseña -->
<?php
}else if($entidad=="juegos"){
?>
<!-- Modal de Añadir/Modificar Generos y Plataformas  -->
<div class="modal fade" id="modificacionGeneroPlat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Modification of Genres and Platforms</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_GenPlat">
                <div>
                    <h3 for="Generos">Genres: </h3>
                    <br><br>
                    <div class="generosChecks">
                    <?php
                    foreach($generos as $genero){
                    ?>
                    <div class="form-check">
                        <input class="generoCheck form-check-input" type="checkbox" value="<?php echo $genero["id"] ?>">
                        <label class="form-check-label"><?php echo $genero["Nombre"] ?></label>
                    </div>
                    <?php
                    }
                    ?>
                    </div>
                    <br>
                    <br>
                    <h3 for="Plataformas">Platforms: </h3>
                    <br><br>
                    <div class="plataformasChecks">
                    <?php
                    foreach($plataformas as $plataforma){
                    ?>
                    <div class="form-check">
                        <input class="plataformaCheck form-check-input" type="checkbox" value="<?php echo $plataforma["id"] ?>">
                        <label class="form-check-label"><?php echo $plataforma["Nombre"] ?></label>
                    </div>
                    <?php
                    }
                    ?>
                    </div>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal_GenPlat" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancel</button>
                <button id="btn_addGenPlat" type="button" class="boton-perso">Add/Modify Genres and Platforms</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Añadir/Modificar Generos y Plataformas -->
<?php
}
?>



<div class="content">
    <input type="hidden" id="entidad" name="entidad" value="<?php echo $entidad ?>">
    <h2 class="h2-entidad ms-3">Tabla <?php echo $entidad ?></h2>
    <br><br>
    <a class="enlace-vuelta enlace" href="/panelAdmin">< Back to Administrator Panel</a>
    
    <button id="btn_crear_dato" class="boton-perso ms-3">Add <?php echo $entidad ?></button>
    <br><br>

    <div class="paginacion"></div>
    <?php
    if($entidad!="post_vendidos"){
    ?>
        <div class="buscador">
            <label for="buscador"><strong>Search (Name / Title):</strong></label>
            <br>
            <div class="input-group">
                <input id="busqueda" class="form-control" type="text">
                <button id="btnBusqueda" class="btn btn-outline-secondary bg-white" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    <?php
    }
    ?>
    
    <table id="tabla-datos" class="table table-striped table-dark tabla-datos"></table>

    <div id="tabla-movil" class="tabla-movil"></div> 
</div>

<script src="/public/JS/tablaAdmin.js"></script>

<?php
require_once __DIR__ . '/Templates/final.php';
?>