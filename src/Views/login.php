<?php
$css = "login_register";
require_once 'Templates/inicio.php';

if(!empty($_SESSION)){
    header("Location: /TDG/");
}

?>

<div class="tarjeta-login">
    <h2 class="nombreApp">To Do Games</h2>
    <img class="logo-TDG" src="public/IMG/TDG-Logo.png" alt="">
    <h2 class="text-center">Iniciar Sesión</h2>
    <form action="#" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo:</label>
            <input type="text" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button id="submit-login" type="submit" class="btn btn-primary">Iniciar Sesión</button>
        <br><br>
        <a class="enlace" href="/TDG/">< Volver al Inicio</a>    
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#submit-login").click(function(event) {
            event.preventDefault();

            let correo = $("#correo").val();
            let password = $("#password").val();

            let formData = new FormData();
            formData.append("correo", correo);
            formData.append("password", password);

            $.ajax({
                url: '/TDG/AJAX/CompruebaLogin',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.href="/TDG/";
                },
                error: function(error){
                    // Agregar p de error.
                    console.log(error)
                }

            });
        })
    });
</script>

<?php
include_once __DIR__ . "./Templates/footer.php";
?>

<?php
require_once 'Templates/final.php';
