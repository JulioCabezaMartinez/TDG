<?php
$css = "login";
require_once 'Templates/inicio.php';

require_once 'Templates/header.php';
?>

<div class="tarjeta-login">
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h1 class="text-center">Registro</h1>
    <form action="/TDG/registrar-usuario" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control w-75" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido:</label>
            <input type="text" class="form-control w-75" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo:</label>
            <input type="text" class="form-control w-75" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (Opcional):</label>
            <input type="file" name="imagen_perfil" id="imagen_perfil">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Nick de Usuario:</label>
            <input type="text" class="form-control w-75" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control w-75" id="password" name="password" required>
        </div>
        <button type="submit" id="btn-registro" class="btn registro">Registrarse</button>
    </form>
</div>

<?php
require_once 'Templates/final.php';