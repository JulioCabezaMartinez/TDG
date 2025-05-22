"use strict";

function buscarFiltros() {
    let filtros={};
    document.querySelectorAll('[id$="Input"]').forEach(function (input) {
        let valor = input.value;
        let clave = input.id.replace('Input', '');

        if (valor) {
            if (clave == "nombre") {
                filtros[clave] = "%" + valor + "%";
            } else {
                filtros[clave] = valor;
            }
        }
    });

    // Recoger el valor de los radio buttons con el nombre 'stock'
    const radios = document.getElementsByName('stock');
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            filtros["Stock"] = radios[i].value;
        }
    }

    return filtros;
}

/* Eventos click de Click de la Página */
function eventos() {
    document.getElementById("boton_filtro").addEventListener("click", function () {

        document.querySelectorAll(".filtros_desplegable").forEach(function (el) {
            el.classList.toggle("active");
        });
    });

    // Antigua idea de filtros en scroll.

    // document.querySelectorAll(".opcion_filtro").forEach(function (el) {
    //     el.addEventListener("click", function () {
    //         const filtrosActivos = document.querySelector(".filtros_activos");

    //         const div = document.createElement("div");
    //         div.className = "card-item swiper-slide";
    //         div.innerHTML = `<i class='fa-solid fa-xmark eliminar-filtro'></i> ${this.value}`;

    //         filtrosActivos.appendChild(div);

    //         const numeroDeSlides = filtrosActivos.children.length;

    //         if (window.swiper) {
    //             swiper.update();
    //         }
    //     });
    // });

    // document.addEventListener("click", function (e) {
    //     if (e.target && e.target.classList.contains("eliminar-filtro")) {
    //         const slide = e.target.closest(".swiper-slide");
    //         if (slide) {
    //             slide.remove();
    //         }

    //         if (window.swiper) {
    //             swiper.update();
    //         }
    //     }
    // });

    // Aplicar Filtros
    document.getElementById("aplicarFiltros").addEventListener("click", function(){
        let filtros=buscarFiltros();

        paginacion(undefined, filtros);
    });

    // Quitar filtros
    document.getElementById("resetFiltros").addEventListener("click", function(){
        document.querySelectorAll('[id$="Input"]').forEach(function(input){
            input.value="";
        })

        paginacion();
    });
}

/* Paginación de listas */
function crearTabla(ventas) {
    let lista_ventas=document.getElementById("lista_ventas");
    lista_ventas.innerHTML='';

    // Recorremos la lista de ventas y generamos los elementos HTML
    ventas.forEach(venta => {
        // Crear el enlace
        const link = document.createElement('a');
        link.href = `/TDG/ventas/view?id=${venta.id}`;

        // Crear el div de la venta
        const ventaDiv = document.createElement('div');
        ventaDiv.id = `${venta.id}`;
        ventaDiv.classList.add('venta');

        // Crear la imagen
        const img = document.createElement('img');
        img.src = `/TDG/public/IMG/${venta.img_venta}`;

        // Crear la sección de información del juego
        const infoJuegoDiv = document.createElement('div');
        infoJuegoDiv.classList.add('info_juego');

        // Crear el título
        const tituloH1 = document.createElement('h1');
        tituloH1.classList.add('titulo');
        tituloH1.textContent = venta.Titulo;

        // Crear el precio
        const precioP = document.createElement('p');
        precioP.classList.add('precio');
        const precioStrong = document.createElement('strong');
        precioStrong.textContent = `${venta.Precio}€`;
        precioP.appendChild(precioStrong);

        // Crear el Estado
        const estadoP = document.createElement('p');
        estadoP.classList.add('estado_producto');
        estadoP.innerHTML = `<strong>Estado:</strong> ${venta.Estado}`;

        // Crear la consola
        const consolaP = document.createElement('p');
        consolaP.classList.add('consola_producto');
        consolaP.innerHTML = `<strong>Estado:</strong> ${venta.Consola}`;
        

        // Añadir todos los elementos a sus respectivos contenedores
        infoJuegoDiv.appendChild(tituloH1);
        infoJuegoDiv.appendChild(estadoP);
        infoJuegoDiv.appendChild(consolaP);
        infoJuegoDiv.appendChild(precioP);
        ventaDiv.appendChild(img);
        ventaDiv.appendChild(infoJuegoDiv);
        link.appendChild(ventaDiv);

        lista_ventas.appendChild(link);
    });
}

/* Permite ver una paginación con todas las páginas que va a tener la página */
function paginas(pagina, total_paginas){

    let filtros=buscarFiltros();

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
                paginacion(i, filtros); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginacion(nPagina=null, filtros={}) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 4; // Número de productos por página.
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
    formData.append("filtros", JSON.stringify(filtros));

    // console.log(formData.get("filtros"));

    fetch("/TDG/AJAX/lista_ventas", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Ventas", data.ventas);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTabla(mapRespuesta.get("Ventas"));
        paginas(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

/* Buscamos el hidden que indica que se ha buscado por la busqueda y hacemos la busqueda con filtros, en caso de que no este hacemos una busqueda normal */
let busquedaHeader=document.getElementById("hiddenBusqueda");


paginacion();
eventos();