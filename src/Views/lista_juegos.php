<?php

$css = 'lista_juegos';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 class="text-center mt-4">Juegos</h3>

<!-- Menu de Filtros -->
<div class="filtros_desplegable">
    <div class="filtros_texto">
        <p>Filtros</p>
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
    </div>

    <div class="filtros_opciones">
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion1" name="opcion1" value="opcion1">
            <label for="opcion1">Opción 1</label>
        </div>
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion2" name="opcion2" value="opcion2">
            <label for="opcion2">Opción 2</label>
        </div>
        <div>
            <input class="opcion_filtro" type="checkbox" id="opcion3" name="opcion3" value="opcion3">
            <label for="opcion3">Opción 3</label>
        </div>
    </div>
</div>
<!-- Menu de Filtros -->

<div id="filtros">
    <button id="boton_filtro" class="btn_filtros">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>

    <!-- <hr> Poner una barra en vertical para separar el boton de los filtros -->

    <!-- Carrusel de Filtros activos -->
    <div class="swiper">
        <div class="card-wrapper">
            <div class="filtros_activos card-list swiper-wrapper">

            </div>
        </div>
    </div>

</div>

<div class="paginacion"></div>

<div id="list_juegos"></div>

<div class="paginacion"></div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="/TDG/public/JS/carrusel.js"></script>

<script src="/TDG/public/JS/lista_juegos.js" ></script>

<script>
    // JQuery de Añadir a listas.
    $(document).ready(function() {

        $(document).on("click", ".btn_listas i", function(){

            if($(this).hasClass("fa-solid")){ // En caso de que el boton esté coloreado, es decir que el juego esté en la lista, se elimina de esta.

                console.log("Eliminando");

                let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

                let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
                let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

                let formData = new FormData();
                formData.append("id_juego", id_juego);
                formData.append("lista", lista);

                $.ajax({
                    url: "/TDG/AJAX/eliminarJuegoLista",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        let color_boton = getComputedStyle(boton).borderColor;
                        boton.style.color = color_boton;
                        $(boton).toggleClass("fa-solid");
                        $(boton).toggleClass("fa-regular");
                    }
                });

            }else if($(this).hasClass("fa-regular")){ // En caso de que el boton NO esté coloreado, es decir que el juego NO esté en la lista, se añade a esta.
                
                console.log("Añadiendo");

                let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

                let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
                let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

                let formData = new FormData();
                formData.append("id_juego", id_juego);
                formData.append("lista", lista);

                $.ajax({
                    url: "/TDG/AJAX/addJuegoLista",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        let color_boton = getComputedStyle(boton).borderColor;
                        boton.style.color = color_boton;
                        $(boton).toggleClass("en-lista");
                        $(boton).toggleClass("fa-solid");
                        $(boton).toggleClass("fa-regular");
                    }
                });
            }
        });
    });
</script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>