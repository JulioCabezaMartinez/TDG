"use strict";

import { verMas } from "./Modules/utils.js";

function eventos() {

    // Referencias a los elementos
    const modalElement = document.getElementById("creacion_review_modal");

    const btnAddReview = document.getElementById("add-review");
    const btnAgregarReview = document.getElementById("btn_agregar_review");
    const btnCerrarReview = document.getElementById("btn_cerrar_creacion_review");

    const idJuegoHidden = document.getElementById("id_juego_hidden");
    const contenidoReview = document.getElementById("contenido_review");


    // Inicializar el modal de Bootstrap
    const reviewModal = new bootstrap.Modal(modalElement);

    // Mostrar modal
    btnAddReview.addEventListener("click", function () {
        reviewModal.show();
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

        fetch("/TDG/AJAX/lista_review", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                        icon: "success",
                        title: "Review publicada con exito",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                console.log(data);
            })
            .catch(error => {
                Swal.fire({
                        icon: "error",
                        title: "Error en el Servidor",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                console.error("Error en la petición AJAX:", error);
            });
    });

    verMas();
}

eventos();
