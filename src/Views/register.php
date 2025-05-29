<?php
$css = "login_register";
require_once 'Templates/inicio.php';

?>

<div class="tarjeta-login">
    <h2 class="nombreApp">To Do Games</h2>
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h2 id="texto-registro" class="text-center">Registro</h2>
    <form action="#" method="POST" class="mt-4" id="register-form" enctype="multipart/form-data">
        <p class="d-none" id="error-campos"></p>
        <div class="mb-3">
            <label for="nombre" class="form-label">*Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="Pepe" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">*Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="Cabeza" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">*Correo:</label>
            <input type="text" class="form-control" id="email" name="email" value="pepe@cabeza.es" required>
            <p class="d-none" id="error_correo"></p>
        </div>
        <div class="mb-3">
            <label for="imagen_perfil" class="form-label">Imagen (Opcional):</label>
            <input class="imagen_form form-control" type="file" name="imagen_perfil" id="imagen_perfil">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">*Dirección de envio:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="C/Cuarta, 54" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">*Nick de Usuario:</label>
            <input type="text" class="form-control" id="nick" name="nick" value="PepeCabeza" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">*Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" value="@BladeMaster_29" required>
            <p class="d-none" id="error_password"></p>
            <br>
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
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">*Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="confirm" name="confirm" value="@BladeMaster_29" required>
            <p class="d-none" id="error_confirm"></p>
        </div>
        <button type="submit" id="btn-registro" class="registro boton-perso">Registrarse</button>
        <br><br>
        <a class="enlace" href="/TDG/">< Volver al Inicio</a>
    </form>
</div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<script src="/TDG/public/JS/register.js"></script>

<?php
require_once 'Templates/final.php';