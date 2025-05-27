"use strict";

export function verMas() {
    const contenedor = document.getElementById("lista_reviews");

    if (!contenedor) return;

    contenedor.addEventListener("click", function (event) {
        const toggleBtn = event.target.closest(".review_ver_mas_container");
        if (!toggleBtn) return;

        const review = toggleBtn.closest(".review");
        if (review) {
            const reviews_texto = review.getElementsByClassName("review_texto");

            for (let texto of reviews_texto) {
                texto.classList.toggle("d-none");
            }
        }
    });
}
