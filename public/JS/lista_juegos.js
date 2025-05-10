// $(document).ready(function() {
//         $("#boton_filtro").click(function() {
//             console.log("click");
//             $(".filtros_desplegable").toggleClass("active");
//         });

//         $(".opcion_filtro").click(function() {
//             $(".filtros_activos").append("<div class='card-item swiper-slide'> <i class='fa-solid fa-xmark eliminar-filtro'></i>" + $(this).val() + "</div>");

//             numeroDeSlides = $(".filtros_activos").children().length; // Obtiene el número de slides actuales.

//             if (window.swiper) {
//                 swiper.update(); // Actualiza el swiper para que reconozca los nuevos elementos añadidos.
//             }
//         });

//         $(document).on("click", ".eliminar-filtro", function() {
//             // Eliminamos el slide padre (el div .swiper-slide)
//             $(this).closest(".swiper-slide").remove();

//             // Despues habría que eliminar el filtro de la lista de filtros activos y de la consulta.

//             // Actualizamos Swiper para que se entere del cambio
//             if (window.swiper) {
//                 swiper.update();
//             }
//         });

//         // AJAX para añadir el juego a las listas.

//         $(".btn_listas i").click(function() {

//             if($(this).hasClass("fa-solid")){ // En caso de que el boton esté coloreado, es decir que el juego esté en la lista, se elimina de esta.

//                 console.log("Eliminando");

//                 let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

//                 let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
//                 let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

//                 $.ajax({
//                     url: "/TDG/src/AJAX/AJAX.listas.php",
//                     type: "POST",
//                     data: {
//                         mode: "delete_juego_lista",
//                         id_juego: id_juego,
//                         lista: lista,
//                         // El id de Usuario lo conseguimos desde la sesión del usuario en el archivo de AJAX.
//                     },
//                     success: function(response) {
//                         console.log(response);
//                         let color_boton = getComputedStyle(boton).borderColor;
//                         boton.style.color = color_boton;
//                         $(boton).toggleClass("fa-solid");
//                         $(boton).toggleClass("fa-regular");
//                     }
//                 });

//             }else if($(this).hasClass("fa-regular")){ // En caso de que el boton NO esté coloreado, es decir que el juego NO esté en la lista, se añade a esta.
                
//                 console.log("Añadiendo");

//                 let boton = this; // Guardamos el icono en una variable para poder cambiar su color.

//                 let id_juego = $(this).attr("id").split("@")[1]; // Obtener el ID del juego desde el atributo id del icono.
//                 let lista = $(this).attr("id").split("@")[0]; // Obtener la lista desde el atributo id del icono. 

//                 $.ajax({
//                     url: "/TDG/src/AJAX/AJAX.listas.php",
//                     type: "POST",
//                     data: {
//                         mode: "add_juego_lista",
//                         id_juego: id_juego,
//                         lista: lista,
//                         // El id de Usuario lo conseguimos desde la sesión del usuario en el archivo de AJAX.
//                     },
//                     success: function(response) {
//                         console.log(response);
//                         let color_boton = getComputedStyle(boton).borderColor;
//                         boton.style.color = color_boton;
//                         $(boton).toggleClass("en-lista");
//                         $(boton).toggleClass("fa-solid");
//                         $(boton).toggleClass("fa-regular");
//                     }
//                 });
//             }
//         });
//     });


/* Paginación de listas */

function crearTabla(juegos) {
    let list_juegos=document.getElementById("list_juegos");
    list_juegos.innerHTML='';

    const iconos = [
        ['wish', 'heart'],
        ['back', 'clock'],
        ['comp', 'circle-check'],
        ['play', 'circle-play']
    ];

    juegos.forEach(juego => {
        // Crear contenedor principal
        const divJuego = document.createElement('div');
        divJuego.className = 'juego';

        // Imagen
        const img = document.createElement('img');
        img.src = juego.Imagen;
        img.alt = '';
        divJuego.appendChild(img);

        // Info del juego
        const infoJuego = document.createElement('div');
        infoJuego.className = 'info_juego';

        // Enlace y título
        const enlace = document.createElement('a');
        enlace.className = 'enlace_juego';
        enlace.href = '#';

        const h1 = document.createElement('h1');
        h1.textContent = juego.Nombre;

        enlace.appendChild(h1);
        infoJuego.appendChild(enlace);

        // Calificación
        const pCal = document.createElement('p');
        pCal.innerHTML = `<strong>Calificación:</strong> ${juego.calificacion} / 5`;
        infoJuego.appendChild(pCal);

        // Año de salida
        const pFecha = document.createElement('p');
        pFecha.innerHTML = `<strong>Fecha de salida:</strong> ${juego.Anyo_salida}`;
        infoJuego.appendChild(pFecha);

        // Botones/íconos
        const btnListas = document.createElement('div');
        btnListas.className = 'btn_listas';

        iconos.forEach(([prefix, icono], i) => {
            const tipo = juego.estados[i] ? 'fa-solid' : 'fa-regular';
            const iElem = document.createElement('i');
            iElem.id = `${prefix}@${juego.id}`;
            iElem.className = `icono ${tipo} fa-${icono}`;
            btnListas.appendChild(iElem);
        });

        infoJuego.appendChild(btnListas);
        divJuego.appendChild(infoJuego);
        
        list_juegos.appendChild(divJuego);
    });
}

function paginas(pagina, total_paginas){
    const paginas= document.getElementsByClassName("paginacion");

    for (let contenedorPag of paginas){
        contenedorPag.innerHTML='';
        const nav = document.createElement('nav');
        nav.className = 'pag';

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        let numero_inicio = 1;

        if ((pagina - 4) > 1) {
        numero_inicio = pagina - 4;
        }

        let numero_fin = numero_inicio + 8;

        if (numero_fin > total_paginas) {
        numero_fin = total_paginas;
        }

        for (let i = numero_inicio; i <= numero_fin; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i == pagina ? ' active' : '');

        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = i;

        a.addEventListener('click', function (e) {
            e.preventDefault();
            paginacion(i); // Llamada a la función
        });

        li.appendChild(a);
        ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginacion(nPagina=null) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.


    let limite = 5; // Número de juegos por página.
    let inicio = 0;

    if (pagina <= 0) {
        pagina = 1;
    } else {
        inicio = (pagina - 1) * limite; // 5 juegos por página. 
    }

    // Body del AJAX.
    let formData = new FormData();
   
    formData.append("pagina", pagina);
    formData.append("inicio", inicio);
    formData.append("limite", limite);

    fetch("/TDG/AJAX/lista_juegos", {
        method: "POST",
        body: formData

    }).then(response => response.json())
        .then(data => {

            crearTabla(data.juegos);
            paginas(data.pagina, data.total_paginas);

        }).catch(err=>console.log(err));
}

paginacion();
