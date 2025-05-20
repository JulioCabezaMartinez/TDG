// JavaScript de la cabecera

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
}

eventos();