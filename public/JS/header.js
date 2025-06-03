// JavaScript de la cabecera

/**
 * Registra los eventos de interacción para elementos de la cabecera:
 * - Búsqueda de juegos.
 * - Menú hamburguesa para escritorio y móvil.
 */
function eventos(){
    
    document.getElementById("btnBusquedaJuego").addEventListener("click", function(){
        let busqueda=document.getElementById("busquedaJuego");

        if(busqueda.value){
            window.location.href="/TDG/juegos?juego="+busqueda.value;
        }
    });

    
    document.getElementById("opciones").addEventListener("click", function (e) {
        document.querySelectorAll(".menu-hamburguesa-escritorio").forEach(function (el) {
            el.classList.toggle("active");
        });

        const chevron = this.querySelector(".fa-chevron-down");
        chevron.classList.toggle("rotado");
    });  

    
    document.getElementById("hamBtn").addEventListener("click", function (e) {
        document.querySelectorAll(".menu-hamburguesa").forEach(function (el) {
            el.classList.toggle("active");
        });
    });
}

eventos();