<?php
$css = "login_register";
require_once 'Templates/inicio.php';
?>

<div class="tarjeta-login">
    <h1 class="">TO Do Games</h1>
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h2 class="text-center">Iniciar Sesión</h2>
    <form action="#" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario:</label>
            <input type="text" class="form-control w-75" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control w-75" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
</div>

<?php
require_once 'Templates/final.php';
