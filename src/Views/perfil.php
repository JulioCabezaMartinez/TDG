<?php

$css = 'perfil';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';

?>

<!-- Modal de creación y modificación de Datos -->
<div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificacion-dato-header">Modificación de <?php $perfil["Nick"] ?></h5>

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
                                case "Password":
                            ?>
                                <!-- El campo contraseña no se puede modificar por aqui -->
                            <?php
                                break;
                                case "Admin":
                            ?>
                                <!-- El campo Admin no se puede modificar por aqui -->
                            <?php
                                    break;
                                case "Premium":
                            ?>
                                <!-- El campo Premium no se puede modificar por aqui -->
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
                <button id="btn_modificar" type="button" class="boton-perso" data-dismiss="modal">Modificar Usuario</button>
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
                <h5 class="modal-title" id="modificacion-dato-header">Cambio de Contraseña</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_usuario_pass">
                <div>
                    <label class="form-label" for="password_actual">Contraseña Actual: </label>
                    <input class="form-control" type="password" id="contraseña_actual">
                    <br>
                    <label class="form-label" for="password">Contraseña Nueva: </label>
                    <input class="form-control" type="password" id="contraseña_cambio">
                    <!-- Barra de progreso -->
                    <div class="progress mt-2">
                        <div id="password-strength-bar" class="progress-bar" style="width: 0%;"></div>
                    </div>

                    <!-- Mensajes de validación -->
                    <ul class="list-unstyled mt-2">
                        <li id="length" class="text-danger">❌ Mínimo 8 caracteres</li>
                        <li id="uppercase" class="text-danger">❌ Al menos una mayúscula</li>
                        <li id="lowercase" class="text-danger">❌ Al menos una minúscula</li>
                        <li id="number" class="text-danger">❌ Al menos un número</li>
                        <li id="special" class="text-danger">❌ Al menos un carácter especial (@, #, $, etc.)</li>
                    </ul>
                    <br>
                    <label class="form-label" for="confirm">Confirmar Contraseña: </label>
                    <input class="form-control" type="password" id="confirm_cambio">
                    <br>
                    <p style="color: red;" id="error_pass"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal_pass" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancelar</button>
                <button id="btn_cambiarPass" type="button" class="boton-perso">Cambiar Contraseña</button>
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
                <button id="btn_modificar@<?php echo $perfil["id"] ?>" class="dropdown-item btn btn-primary modificar-dato">Modificar</button>
                <button id="btn_cambPass@<?php echo $perfil["id"] ?>" class="dropdown-item btn btn-primary cambiarPassword">Cambiar Contraseña</button>
            </div>
        <?php
        }
        ?>
    </div>


    <?php
    if ($perfil["Premium"] == 0) {
    ?>
        <a href="/TDG/perfil/Premium">
            <button class="getPremium boton-perso">Conseguir Premium <i class="fa-solid fa-crown"></i></button>
        </a>
    <?php
    }
    ?>
</div>


<a href="/TDG/perfil/ventas">
    <button class="boton-perso">Ver Ventas y Compras</button>
</a>



<div class="juegos">
    <h3>Deseados</h3>
    <div class="paginacionWishlist"></div>
    <div id="wishlist"></div>
</div>

<div class="juegos">
    <h3>Jugando</h3>
    <div class="paginacionPlaying"></div>
    <div id="playing"></div>
</div>

<div class="juegos">
    <h3>Completados</h3>
    <div class="paginacionCompleted"></div>
    <div id="completed"></div>
</div>

<div class="juegos">
    <h3>Pendientes</h3>
    <div class="paginacionBacklog"></div>
    <div id="backlog"></div>
</div>

<script src="/TDG/public/JS/perfil.js"></script>

<?php
include_once __DIR__ . "/Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>