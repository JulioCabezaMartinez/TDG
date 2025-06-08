<?php
$css = "login_register";
require_once 'Templates/inicio.php';

?>

<div class="tarjeta-login">
    <h2 class="nombreApp">To Do Games</h2>
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h2 id="texto-registro" class="text-center">Register</h2>
    <form action="#" method="POST" class="mt-4" id="register-form" enctype="multipart/form-data">
        <p class="d-none" id="error-campos"></p>
        <div class="mb-3">
            <label for="nombre" class="form-label">*Name:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">*Surname:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">*Email:</label>
            <input type="text" class="form-control" id="email" name="email" required>
            <p class="error" id="error_correo"></p>
        </div>
        <div class="mb-3">
            <label for="imagen_perfil" class="form-label">Image (Opcional):</label>
            <input class="imagen_form form-control" type="file" name="imagen_perfil" id="imagen_perfil">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">*Address:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">*User's Nick:</label>
            <input type="text" class="form-control" id="nick" name="nick" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">*Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <p class="error" id="error_password"></p>
            <br>
            <!-- Barra de progreso -->
            <div class="progress mt-2">
                <div id="password-strength-bar" class="progress-bar" style="width: 0%;"></div>
            </div>

            <!-- Mensajes de validación -->
            <ul class="list-unstyled mt-2">
                <li id="length" class="error"> <i class="fa-solid fa-square-xmark"></i> Minimum 8 characters</li>
                <li id="uppercase" class="error"> <i class="fa-solid fa-square-xmark"></i> At least one capital letter</li>
                <li id="lowercase" class="error"> <i class="fa-solid fa-square-xmark"></i> At least one lowercase</li>
                <li id="number" class="error"> <i class="fa-solid fa-square-xmark"></i> At least one number</li>
                <li id="special" class="error"> <i class="fa-solid fa-square-xmark"></i> At least one special character (@, #, $, etc.)</li>
            </ul>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">*Confirm Password:</label>
            <input type="password" class="form-control" id="confirm" name="confirm" required>
            <p class="error" id="error_confirm"></p>
        </div>
        <br><br>
        <p class="error" id="error_global"></p>
        <button type="submit" id="btn-registro" class="registro boton-perso">Register</button>
        <br><br>
        <a class="enlace" href="/">< Back to Home</a>
    </form>
</div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<script src="/public/JS/register.js"></script>

<?php
require_once 'Templates/final.php';