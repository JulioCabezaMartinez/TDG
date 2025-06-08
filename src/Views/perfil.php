<?php

$css = 'perfil';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';

?>

<!-- Modal de creación y modificación de Datos -->
<div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Modification of <?php $perfil["Nick"] ?></h5>

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
                                case "Admin":
                                // Sin Admin
                                    break;
                                case "Premium":
                                // Sin Premium
                                    break;
                                // No quiero que se modifique la contraseña por texto.
                                case "Password": 
                                break;
                                case "Imagen_usuario":
                            ?>
                                <label for="<?php echo $columna ?>Label"><strong>Imagen:</strong></label>
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
                ?>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancel</button>
                <button id="btn_modificar" type="button" class="boton-perso" data-dismiss="modal">Modificate User</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación y modificación de Datos -->

<!-- Modal de cambio de Contraseña -->
<div class="modal fade" id="modificacionPass" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Change password</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_pass">
                <div>
                    <label class="form-label" for="password_actual">Actual Password: </label>
                    <input class="form-control" type="password" id="contraseña_actual">
                    <br>
                    <label class="form-label" for="password">New Password: </label>
                    <input class="form-control" type="password" id="contraseña_cambio">
                    <!-- Barra de progreso -->
                    <div class="progress mt-2">
                        <div id="password-strength-bar" class="progress-bar" style="width: 0%;"></div>
                    </div>

                    <!-- Mensajes de validación -->
                    <ul class="list-unstyled mt-2">
                        <li id="length" class="text-danger">❌ Minimum 8 characters</li>
                        <li id="uppercase" class="text-danger">❌ At least one capital letter</li>
                        <li id="lowercase" class="text-danger">❌ At least one lowercase letter</li>
                        <li id="number" class="text-danger">❌ At least one number</li>
                        <li id="special" class="text-danger">❌ At least one special character (@, #, $, etc.)</li>
                    </ul>
                    <br>
                    <label class="form-label" for="confirm">Confirm Password: </label>
                    <input class="form-control" type="password" id="confirm_cambio">
                    <br>
                    <p style="color: red;" id="error_pass"></p>
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

<div id="perfil">
    <?php
    if ($perfil["Premium"] == 1) {
    ?>
        <i class="fa-solid fa-crown"></i>
    <?php
    }
    ?>
    <div id="perfil-header">
        <h2><?php echo $perfil['Nick'] ?></h2>
        <?php
        if($perfil["id"]==$_SESSION["usuarioActivo"]){
        ?>
            <div class="dropdown-toggle enlace" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-gear opciones"></i>
            </div>
            <div class="dropdown-menu">
                <button id="btn_modificar@<?php echo $perfil["id"] ?>" class="dropdown-item btn btn-primary modificar-dato">Modificate</button>
                <button id="btn_cambPass@<?php echo $perfil["id"] ?>" class="dropdown-item btn btn-primary cambiarPassword">Change Password</button>
            </div>
        <?php
        }
        ?>
    </div>


    <?php
    if ($perfil["Premium"] == 0) {
    ?>
        <a href="/perfil/Premium">
            <button class="getPremium boton-perso">Get Premium <i class="fa-solid fa-crown"></i></button>
        </a>
    <?php
    }
    ?>
</div>


<a href="/perfil/ventas">
    <button class="boton-perso">View Sales and Purchases</button>
</a>



<div class="juegos">
    <h3>Wishlist</h3>
    <div class="paginacionWishlist"></div>
    <div id="wishlist"></div>
</div>

<div class="juegos">
    <h3>Playing</h3>
    <div class="paginacionPlaying"></div>
    <div id="playing"></div>
</div>

<div class="juegos">
    <h3>Completed</h3>
    <div class="paginacionCompleted"></div>
    <div id="completed"></div>
</div>

<div class="juegos">
    <h3>Pending</h3>
    <div class="paginacionBacklog"></div>
    <div id="backlog"></div>
</div>

<script src="/public/JS/perfil.js"></script>

<?php
include_once __DIR__ . "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>