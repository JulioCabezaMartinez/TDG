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

<?php include __DIR__."/Templates/paginacion.php" ?>

<div class="list_juegos">
    <?php
    foreach ($juegos as $juego) { // Cambiar Calificacion por Generos del Juego.

        echo "<div class='juego'>
                    <img src='{$juego['Imagen']}' alt=''>
                    <div class='info_juego'>
                        <a class='enlace_juego' href='#'>
                            <h1>{$juego['Nombre']}</h1>
                        </a>
                        <p><strong>Calificación:</strong> {$juego['calificacion']} / 5</p> 
                        <p><strong>Fecha de salida:</strong> {$juego['Anyo_salida']}</p>
                        <div class='btn_listas'>
                        ";

                        $iconos = [
                            ["wish", "heart"],
                            ["back", "clock"],
                            ["comp", "circle-check"],
                            ["play", "circle-play"]
                        ];
                        
                        foreach ($iconos as $i => [$prefix, $icono]) {
                            $tipo = $juego["estados"][$i] ? "fa-solid" : "fa-regular";
                            echo "<i id='{$prefix}@{$juego['id']}' class='icono {$tipo} fa-{$icono}'></i>";
                        }
        echo "</div>
            </div>
        </div>";
    }
    ?>
</div>

<?php include __DIR__."/Templates/paginacion.php" ?>

<!-- JS del Swiper (Carrusel) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Script de inicio del Swiper -->
<script src="/TDG/public/JS/carrusel.js"></script>

<script>
    $(document).ready(function() {
        $("#boton_filtro").click(function() {
            console.log("click");
            $(".filtros_desplegable").toggleClass("active");
        });

        $(".opcion_filtro").click(function() {
            $(".filtros_activos").append("<div class='card-item swiper-slide'> <i class='fa-solid fa-xmark eliminar-filtro'></i>" + $(this).val() + "</div>");

            numeroDeSlides = $(".filtros_activos").children().length; // Obtiene el número de slides actuales.

            if (window.swiper) {
                swiper.update(); // Actualiza el swiper para que reconozca los nuevos elementos añadidos.
            }
        });

        $(document).on("click", ".eliminar-filtro", function() {
            // Eliminamos el slide padre (el div .swiper-slide)
            $(this).closest(".swiper-slide").remove();

            // Despues habría que eliminar el filtro de la lista de filtros activos y de la consulta.

            // Actualizamos Swiper para que se entere del cambio
            if (window.swiper) {
                swiper.update();
            }
        });

        // AJAX para añadir el juego a las listas.

        $(".btn_listas i").click(function() {

            if($(this).hasClass("fa-solid")){ // En caso de que el boton esté coloreado, es decir que el juego esté en la lista, se elimina de esta.

                console.log("Eliminando");

                let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

                let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
                let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

                $.ajax({
                    url: "/TDG/src/AJAX/AJAX.listas.php",
                    type: "POST",
                    data: {
                        mode: "delete_juego_lista",
                        id_juego: id_juego,
                        lista: lista,
                        // El id de Usuario lo conseguimos desde la sesión del usuario en el archivo de AJAX.
                    },
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

                $.ajax({
                    url: "/TDG/src/AJAX/AJAX.listas.php",
                    type: "POST",
                    data: {
                        mode: "add_juego_lista",
                        id_juego: id_juego,
                        lista: lista,
                        // El id de Usuario lo conseguimos desde la sesión del usuario en el archivo de AJAX.
                    },
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