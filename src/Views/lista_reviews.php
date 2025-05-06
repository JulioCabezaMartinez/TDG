<?php
$css = "lista_reviews";
require_once 'Templates/inicio.php';

require_once 'Templates/header.php';
?>

<!-- Modal de creación de Review -->
<div class="modal fade" id="creacion_review_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Creación Review</h5>
            </div>
            <div class="modal-body">
                <div>
                    <div>
                        <label for="">Nombre Juego:</label>
                        <input id="nombre_juego" class="form-control" type="text" value="<?php echo $juego["Nombre"] ?>" readonly>
                    </div>
                    <br>
                    <div>
                        <label for="">Review:</label>
                        <textarea id="contenido_review" class="form-control" rows="4" type="text"></textarea>
                    </div>
                    <input id="id_juego_hidden" type="hidden" value="<?php echo $_GET["id_juego"] ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_creacion_review" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn_agregar_review" type="button" class="btn btn-primary" data-dismiss="modal">Agregar Review</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<h1 class="text-center mt-4"><?php echo $juego["Nombre"] ?></h1>

<div class="reviews-h2">
    <h2>Reviews</h2>
    <button id="add-review" class="btn-add-review">
        <i class="fa-regular fa-plus"></i>
        <i class="fa-regular fa-comment"></i>
    </button>
</div>

<hr>
<div class="lista_reviews">
    <?php
    foreach ($lista_reviews as $review) {
        $usuario = $usuarioBD->getbyId($review["id_Escritor"]);
        $contenido_reducido = str_split($review['Contenido'], 10)[0];
        $contenido_reducido .= " ...";
    ?>

        <div class="review">
            <div class="cabecera_review">
                <img src="/TDG/public/IMG/Users-img/<?php echo $usuario["Imagen"] ?>" alt=""> <!-- Imagen de usuario del Usuario asociado a la review -->
                <h3><?php echo $usuario["Nombre"] ?></h3> <!-- Nombre del Usuario que ha escrito la reseña -->
            </div>

            <?php
            if (strlen($review['Contenido']) >= 10) {
            ?>
                <p id="texto_reducido" class="review_texto"><?php echo $contenido_reducido; ?></p>
                <p class="review_texto d-none"><?php echo $review['Contenido']; ?></p>
            <?php
            } else {
            ?>
                <p id="texto_reducido" class="review_texto d-none"><?php echo $contenido_reducido; ?></p>
                <p class="review_texto"><?php echo $review['Contenido']; ?></p> <!-- Texto completo de la Review -->
            <?php
            }
            ?>
            <div class="review_footer">
                <div class="review_ver_mas_container">
                    <i class="fa-solid fa-arrow-down"></i>
                    <p class="review_ver_mas">Ver más</p>
                </div>
            </div>
        </div>

    <?php
    }
    ?>
</div>


<script>
    $(document).ready(function() {
        // Modal de Creación de Review

        $("#add-review").click(function() {
            $("#creacion_review_modal").modal('show');
        });

        $("#btn_cerrar_creacion_review").click(function() {
            $("#creacion_review_modal").modal('hide');
            $("#contenido_review").val("");
        });

        // AJAX para enviar la información del modal a la BD.  
        $("#btn_agregar_review").click(function(){
            let id_juego = $("#id_juego_hidden").val();
            let review = $("#contenido_review").val();

            $.ajax({
                url: "/TDG/src/AJAX/AJAX.review.php",
                type: "POST",
                data: {
                    mode: "add_review",
                    id_juego: id_juego,
                    review: review,
                    // El id de Usuario lo conseguimos desde la sesión del usuario en el archivo de AJAX.
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });      
        

        $(".review_ver_mas_container").click(function() {
            const $review = $(this).closest('.review'); // Busca el elemento más cercano con la clase .review.

            $review.find('p.review_texto').toggleClass('d-none'); // Seleccionamos todos los <p> con clase review_texto dentro de esa review
        });
    });
</script>

<?php
require_once 'Templates/final.php';
