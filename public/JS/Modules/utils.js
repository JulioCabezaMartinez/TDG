"use strict";

export function verMas(){

    // Alternar visibilidad del texto de review para verlo completo.
    document.querySelectorAll(".review_ver_mas_container").forEach(function (el) {
        el.addEventListener("click", function () {
            const review = this.closest(".review");
            if (review) {
                const reviews_texto = review.getElementsByClassName("review_texto");

                for (let texto of reviews_texto) {
                    texto.classList.toggle("d-none");
                }
            }
        });
    });
}