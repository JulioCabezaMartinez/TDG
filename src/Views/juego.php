<?php
$css = 'juego';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>
<div class="top_juego">
    <input type="hidden" id="hidden_id_juego" value="<?php echo $juego["id"] ?>">
    <h1><?php echo $juego['Nombre'] ?></h1>

    <img class="imagen_juego" src="<?php echo $juego['Imagen'] ?>" alt="">

    <?php
    if(!empty($_SESSION)){
    ?>
        <div id='btn_listas'></div>
    <?php
    }
    ?>
    
</div>


<div class="info_juego">
    <div class="caracteristicas_juego">

        <div class="generos_juego">
            <h3>Géneros</h3>
            <div class="distr-wrap">
                <?php
                    foreach ($generos as $genero) {
                ?>
                    <p class="btn_redondo"><?php echo $genero['Nombre'] ?></p>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="plataformas_juego">
            <h3>Plataformas</h3>
            <div class="distr-wrap">
                <?php
                    foreach ($plataformas as $plataforma) {
                ?>
                    <p><?php echo $plataforma["Nombre"] ?></p>
                <?php
                    }
                ?>
            </div>
        </div>
        <div>
            <h3>Año de Salida</h3>
            <p>
            <?php
                $fechaOriginal = $juego['Anyo_salida'];
                $fecha = new DateTime($fechaOriginal);
                echo $fecha->format('M j, Y'); // Un formato de fecha más legible.
            ?>
            </p>
        </div>

    </div>

    <div class="descripcion_juego">
        <h2>Descripción (Falta poner boton verMas)</h2>
        <?php echo $juego['Descripcion'] ?>
    </div>

</div>


<!-- Reseñas del Juego -->

<div class="reseñas_juego m-4">
    <a href="/juegos/view/review?id=<?php echo $juego["id"] ?>"><h2 class="enlace">Reseñas de <?php echo $juego["Nombre"] ?></h2></a>
</div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<script>
    // JQuery de Añadir a listas.
    $(document).ready(function() {
        $(document).on("click", ".btn-lista", function(){

            let icono=$(this).find("i");

            if(icono.hasClass("fa-solid")){ // En caso de que el boton esté coloreado, es decir que el juego esté en la lista, se elimina de esta.

                let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
                let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

                let formData = new FormData();
                formData.append("id_juego", id_juego);
                formData.append("lista", lista);

                $.ajax({
                    url: "/AJAX/eliminarJuegoLista",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let respuesta=JSON.parse(response).result;

                        Swal.fire({
                            position: "top",
                            icon: "error",
                            text: respuesta,
                            showConfirmButton: false,
                            timer: 1500,
                            width: "50%",
                            backdrop: false,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        });

                        icono.toggleClass("fa-solid");
                        icono.toggleClass("fa-regular");
                    },
                    error: function(error){
                        console.log(error);
                    }
                });

            }else if(icono.hasClass("fa-regular")){ // En caso de que el boton NO esté coloreado, es decir que el juego NO esté en la lista, se añade a esta.

                let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

                let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
                let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

                let formData = new FormData();
                formData.append("id_juego", id_juego);
                formData.append("lista", lista);

                $.ajax({
                    url: "/AJAX/addJuegoLista",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let respuesta=JSON.parse(response).result;

                        Swal.fire({
                            position: "top",
                            icon: "success",
                            text: respuesta,
                            showConfirmButton: false,
                            timer: 1500,
                            width: "50%",
                            backdrop: false,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        });

                        icono.toggleClass("fa-solid");
                        icono.toggleClass("fa-regular");
                    }
                });
            }
        });
    });
</script>

<?php 
if(!empty($_SESSION)){
?>
    <script src="/public/JS/juego.js"></script>
<?php
}
?>


<?php
require_once __DIR__ . '/Templates/final.php';
?>