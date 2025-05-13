<?php
$css = "login_register";
require_once 'Templates/inicio.php';

if(!empty($_SESSION)){
    header("Location: /TDG/");
}

?>

<div class="tarjeta-login">
    <h1>TO Do Games</h1>
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h2 class="text-center">Registro</h2>
    <form action="/TDG/registrar-usuario" method="POST" class="mt-4" id="register-form">
        <div class="mb-3">
            <label for="nombre" class="form-label">*Nombre:</label>
            <input type="text" class="form-control w-75" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">*Apellido:</label>
            <input type="text" class="form-control w-75" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">*Correo:</label>
            <input type="text" class="form-control w-75" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (Opcional):</label>
            <input class="imagen_form form-control" type="file" name="imagen_perfil" id="imagen_perfil">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">*Nick de Usuario:</label>
            <input type="text" class="form-control w-75" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">*Contraseña:</label>
            <input type="password" class="form-control w-75" id="password" name="password" required>
            <br>
            <!-- Barra de progreso -->
            <div class="progress mt-2 w-75">
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
            <input type="password" class="form-control w-75" id="confirm" name="confirm" required>
        </div>
        <button type="submit" id="btn-registro" class="btn registro">Registrarse</button>
        <a class="enlace ms-3" href="/TDG/">Volver al Inicio</a>
    </form>
</div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<script src="/TDG/public/JS/register.js"></script>

<?php
require_once 'Templates/final.php';