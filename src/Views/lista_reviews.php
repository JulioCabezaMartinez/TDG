<?php
$css = "lista_reviews";
require_once 'Templates/inicio.php';

require_once 'Templates/header.php';
?>

<input type="hidden" name="id_juego" id="id_juego_hidden" value="<?php echo $juego["id"] ?>">

<!-- Modal de creación de Review -->
<div class="modal fade" id="creacion_review_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-background-color">
            <div class="modal-header">
                <h5 class="modal-title" id="header-creacion-review">Creación Review</h5>
                <h5 class="modal-title" id="header-modificacion-review">Modificación Review</h5>
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
                    <input id="id_juego_hidden" type="hidden" value="<?php echo $juego["id"] ?>">
                    <input id="id_review_hidden" type="hidden" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_creacion_review" type="button" class="boton-perso boton-perso-secundario" data-dismiss="modal">Cancelar</button>
                <button id="btn_modificar_review" type="button" class="boton-perso" data-dismiss="modal">Modificar Review</button>
                <button id="btn_agregar_review" type="button" class="boton-perso" data-dismiss="modal">Agregar Review</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<h1 class="text-center m-4"><?php echo $juego["Nombre"] ?></h1>

<div class="reviews-h2">
    <h2>Reviews</h2>
    <div id="add-review" class="btn-add-review">
        <i class="fa-regular fa-plus"></i>
        <i class="fa-regular fa-comment"></i>
    </div>
</div>

<hr>
<div class="paginacion"></div>
<div id="lista_reviews"></div>
<div class="paginacion"></div>

<?php
    include_once __DIR__. "/Templates/footer.php";
?>

<script type="module" src="/TDG/public/JS/lista_review.js"></script> <!-- Se necesita que el HTML trate al script como un modulo para que se puedan importar clases usando import y export -->

<?php
require_once 'Templates/final.php';
