"use strict";

import { verMas } from "./Modules/utils.js";

/**
 * Asigna todos los eventos relacionados con la gestión de reviews,
 * incluyendo creación, modificación, eliminación y control del modal.
 *
 * Eventos incluidos:
 * - Click en `#add-review`: Muestra el modal de creación de review.
 * - Click en `#btn_cerrar_creacion_review`: Cierra el modal y limpia el contenido.
 * - Click en `#btn_agregar_review`: Envía una nueva review mediante `fetch`.
 * - Click en `.btn-eliminar-review`: Muestra confirmación y elimina una review si se acepta.
 * - Click en `.btn-modificar-review`: Muestra el modal con datos cargados para modificar una review.
 * - Click en `#btn_modificar_review`: Envía la review modificada al servidor.
 * 
 */
function eventos() {
    let color_neon=getComputedStyle(document.documentElement).getPropertyValue('--color-borde-neon').trim();

    // Referencias a los elementos
    const entidad="reviews";

    const modalElement = document.getElementById("creacion_review_modal");

    const btnAddReview = document.getElementById("add-review");
    const btnAgregarReview = document.getElementById("btn_agregar_review");
    const btnCerrarReview = document.getElementById("btn_cerrar_creacion_review");

    const idJuegoHidden = document.getElementById("id_juego_hidden");
    const hidden_id_review=document.getElementById("id_review_hidden");
    const contenidoReview = document.getElementById("contenido_review");


    // Inicializar el modal de Bootstrap
    const reviewModal = new bootstrap.Modal(modalElement);

    // Mostrar modal
    btnAddReview.addEventListener("click", function () {
        reviewModal.show();
        contenidoReview.value = "";
        hidden_id_review.value = "";
    });

    // Ocultar modal y limpiar contenido
    btnCerrarReview.addEventListener("click", function () {
        reviewModal.hide();
        contenidoReview.value = "";
    });

    // Enviar AJAX con Fetch al hacer clic en "Agregar review"
    btnAgregarReview.addEventListener("click", function () {
        const id_juego = idJuegoHidden.value;
        const review = contenidoReview.value;

        let formData = new FormData();
        formData.append("id_juego", id_juego);
        formData.append("review", review);

        fetch("/TDG/AJAX/add_review", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                Swal.fire({
                        icon: "success",
                        title: "Review publicada con exito",
                        showConfirmButton: false,
                        timer: 1500,
                        background: "#2C2C2E",
                        color: "#FFFFFF"
                    });
                paginacion(); // Actualizar la lista de reviews
            })
            .catch(error => {
                Swal.fire({
                        icon: "error",
                        title: "Error en el Servidor",
                        showConfirmButton: false,
                        timer: 1500,
                        background: "#2C2C2E",
                        color: "#FFFFFF"
                    });
                console.error("Error en la petición AJAX:", error);
            });
    });

    document.addEventListener("click", (e)=>{
        if (e.target.classList.contains("btn-eliminar-review")) {

            Swal.fire({
            title: "¿Seguro que quieres eliminar la Review?",
            text: "¡No puedes revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: color_neon,
            cancelButtonColor: "#808080",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
            background: "#2C2C2E",
            color: "#FFFFFF"
            
        }).then((result) => {
            if (result.isConfirmed) {

                const id = e.target.id.split("@")[0];

                let formData=new FormData();
                formData.append("id", id);

                fetch("/TDG/AJAX/eliminarReview", {
                    method: "POST",
                    body: formData
                }).then(response => response.text())
                    .then(data => {
                        Swal.fire({
                            title: "Review eliminada",
                            icon: "success",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        }).then(() => {
                            paginacion();
                        });
                    }).catch(error=>{
                        Swal.fire({
                            title: "Error",
                            text: "Algo salio mal en el servidor",
                            icon: "error",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        });
                    });
            }
        });

            

        }
    });

    document.addEventListener("click", (e)=>{
        if (e.target.classList.contains("btn-modificar-review")) {
            document.getElementById("btn_modificar_review").style.display = "block"; // Ocultar botón de modificar
            document.getElementById("btn_agregar_review").style.display = "none"; // Mostrar botón de crear

            document.getElementById("header-creacion-review").style.display = "none"; // Mostrar el header de creación
            document.getElementById("header-modificacion-review").style.display = "block"; // Ocultar el header de modificación

            const modal = new bootstrap.Modal(document.getElementById("creacion_review_modal"));
            modal.show();

            let id=e.target.id.split("@")[0];

            fetch("/TDG/AJAX/datosModificarDato", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({ id, entidad })
            })
            .then(res => res.json())
            .then(data =>{
                contenidoReview.value=data.dato.Contenido;
                hidden_id_review.value=data.dato.id;
            }).catch(error=>{
                console.log(error);
            });
        }
    });

    document.getElementById("btn_modificar_review").addEventListener("click", ()=>{
        let id_juego=idJuegoHidden.value;
        let id_review=hidden_id_review.value;
        let contenido=contenidoReview.value;

        let datos={};

        datos["id_Juego"]=id_juego;
        datos["id"]=id_review;
        datos["Contenido"]=contenido;

        let formData=new FormData();
        formData.append("datos", JSON.stringify(datos));
        formData.append("entidad", "reviews")

        fetch("/TDG/AJAX/modificarDato", {
        method: "POST",
        body: formData
        })
        .then(res => res.text())
        .then(data => {
            Swal.fire({
                icon: "success",
                title: "Review modificada con éxito",
                confirmButtonColor: color_neon,
                background: "#2C2C2E",
                color: "#FFFFFF"
            });
        paginacion(); // Recargar la tabla después de modificar
        })
        .catch(() => {
            Swal.fire({
                icon: "error",
                title: "Error en el servidor",
                confirmButtonColor: color_neon,
                background: "#2C2C2E",
                color: "#FFFFFF"
            });
        });
    });

    verMas();
}

/**
 * Crea y muestra la tabla de reviews dentro del contenedor `#lista_reviews`.
 * 
 * @param {Array<Object>} reviews - Array de objetos review con datos para mostrar.
 * Cada review debe tener propiedades como `Imagen_Usuario`, `Nick_Usuario`, `editable`, `id`, `Contenido` y `contenidoReducido`.
 */
function crearTabla(reviews) {
    let lista_reviews=document.getElementById("lista_reviews");
    lista_reviews.innerHTML='';


    reviews.forEach((review) => {
        const divReview = document.createElement("div");
        divReview.className = "review";

        // Cabecera
        const cabecera = document.createElement("div");
        cabecera.className = "cabecera_review";

        const img = document.createElement("img");
        img.src = `/TDG/public/IMG/Users-img/${review.Imagen_Usuario}`;

        const h3 = document.createElement("h3");
        h3.textContent = review.Nick_Usuario;

        cabecera.appendChild(img);
        cabecera.appendChild(h3);

        if(review.editable){
            const divBotones = document.createElement("div");
            divBotones.className = "review_botones";

            const btnEditar = document.createElement("button");
            btnEditar.id= review.id+"@btn_modificar";
            btnEditar.className = "btn-modificar-review boton-perso";
            
            const iconoEditar = document.createElement("i");
            iconoEditar.className = "fa-solid fa-pen btn-modificar-review";
            iconoEditar.id=review.id+"@icono_modificar";

            btnEditar.appendChild(iconoEditar);

            const btnEliminar = document.createElement("button");
            btnEliminar.id=review.id+"@btn_eliminar"
            btnEliminar.className = "btn-eliminar-review boton-perso boton-perso-secundario";

            const iconoEliminar = document.createElement("i");
            iconoEliminar.className = "fa-solid fa-trash btn-eliminar-review";
            iconoEliminar.id=review.id+"@icono_eliminar"

            btnEliminar.appendChild(iconoEliminar);

            divBotones.appendChild(btnEditar);
            divBotones.appendChild(btnEliminar);
            cabecera.appendChild(divBotones);
        }

        // Contenido
        const pReducido = document.createElement("p");
        pReducido.className = "review_texto";
        pReducido.id = "texto_reducido";

        const pCompleto = document.createElement("p");
        pCompleto.className = "review_texto";

        if (review.Contenido.length > 10) {
            pReducido.textContent = review.contenidoReducido;
            pCompleto.textContent = review.Contenido;
            pCompleto.classList.add("d-none");
        } else {
            pReducido.textContent = review.contenidoReducido;
            pReducido.classList.add("d-none");
            pCompleto.textContent = review.Contenido;
        }

        // Footer
        const footer = document.createElement("div");
        footer.className = "review_footer";


        if (review.Contenido.length > 10){
            const verMasContainer = document.createElement("div");
            verMasContainer.className = "review_ver_mas_container";

            const icono = document.createElement("i");
            icono.className = "fa-solid fa-arrow-down";

            const textoVerMas = document.createElement("p");
            textoVerMas.className = "review_ver_mas";
            textoVerMas.textContent = "Ver más";

            verMasContainer.appendChild(icono);
            verMasContainer.appendChild(textoVerMas);
            footer.appendChild(verMasContainer);
        }
        

        // Ensamblar
        divReview.appendChild(cabecera);
        divReview.appendChild(pReducido);
        divReview.appendChild(pCompleto);
        divReview.appendChild(footer);

       lista_reviews.appendChild(divReview);
    });
}

/**
 * Crea la paginación para la lista de reviews en todos los contenedores `.paginacion`.
 * 
 * @param {number} pagina - Página actual.
 * @param {number} total_paginas - Número total de páginas.
 */
function paginas(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacion");

    for (let contenedorPag of paginas){
        contenedorPag.innerHTML='';

        const nav = document.createElement('nav');
        nav.className = 'pag';

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        /* De esta manera indicamos cuantos numeros queremos que aparezcan a la izquierda y derecha del activo */
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

/**
 * Realiza una petición AJAX para obtener y mostrar la lista de reviews con paginación.
 * 
 * @param {number|null} [nPagina=null] - Número de página a cargar, si es null carga la página 1.
 * @param {Object} [filtros={}] - Opcional, filtros para la petición (no implementado en la función actual).
 */
function paginacion(nPagina=null, filtros={}) {
    let idJuego = document.getElementById("id_juego_hidden");

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
    formData.append("id_juego", idJuego.value);
    // formData.append("filtros", JSON.stringify(filtros));

    // console.log(formData.get("filtros"));

    fetch("/TDG/AJAX/lista_reviews", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Reviews", data.reviews);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTabla(mapRespuesta.get("Reviews"));
        paginas(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

eventos();
paginacion();
