
/* Paginación de listas */
function crearTablaLista(juegos, tipo) {
   let tipo_lista;
    switch (tipo) {
        case 'wishlist':
            tipo_lista = document.querySelector("#wishlist");
            break;
        case 'completed':
            tipo_lista = document.querySelector("#completed");
            break;
        case 'playing':
            tipo_lista = document.querySelector("#playing");
            break;
        case 'backlog':
            tipo_lista = document.querySelector("#backlog");
            break;
    }

    tipo_lista.innerHTML = ''; // Limpiar la lista antes de añadir nuevas ventas

    juegos.forEach(juego => {
        // Si no hay imagen, asignar una por defecto
        if (!juego.Imagen) {
            juego.Imagen = "/TDG/public/IMG/default-game.jpg";
        }

        // Crear el contenedor principal
        const divJuego = document.createElement("div");
        divJuego.className = "juego";

        // Crear el enlace
        const enlace = document.createElement("a");
        enlace.href = `/TDG/juegos/view?id=${juego.id}`;

        // Crear la imagen
        const imagen = document.createElement("img");
        imagen.src = juego.Imagen;
        imagen.alt = "";

        // Crear el título
        const titulo = document.createElement("h4");
        titulo.className = "nombreJuego";
        titulo.textContent = juego.Nombre;

        // Estructurar los elementos
        enlace.appendChild(imagen);
        enlace.appendChild(titulo);
        divJuego.appendChild(enlace);

        tipo_lista.appendChild(divJuego);
    });
}

/* Permite ver una paginación con todas las páginas que va a tener la página */
function paginasWishlist(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionWishlist");

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
                paginacionWishlist(i); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginasCompleted(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionCompleted");

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
                paginacionVentas(i); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginasPlaying(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionPlaying");

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
                paginacionVentas(i); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginasBacklog(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionBacklog");

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
                paginacionCompras(i); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginacionWishlist(nPagina=null) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 6; // Número de juegos por página.
    let inicio = 0;

    if (pagina <= 0) {
        pagina = 1;
    } else {
        inicio = (pagina - 1) * limite; // 6 juegos por página.
    }

    // Body del AJAX.
    let formData = new FormData();
    formData.append("pagina", pagina);
    formData.append("inicio", inicio);
    formData.append("limite", limite);

    fetch("/TDG/AJAX/lista_whislist", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Juegos", data.juegos);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTablaLista(mapRespuesta.get("Juegos"), 'wishlist');
        paginasWishlist(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

function paginacionCompleted(nPagina=null) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 6; // Número de juegos por página.
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

    // console.log(formData.get("filtros"));

    fetch("/TDG/AJAX/lista_completed", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Juegos", data.juegos);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTablaLista(mapRespuesta.get("Juegos"), 'completed');
        paginasCompleted(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

function paginacionPlaying(nPagina=null) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 6; // Número de juegos por página.
    let inicio = 0;

    if (pagina <= 0) {
        pagina = 1;
    } else {
        inicio = (pagina - 1) * limite; // 6 juegos por página.
    }

    // Body del AJAX.
    let formData = new FormData();
    formData.append("pagina", pagina);
    formData.append("inicio", inicio);
    formData.append("limite", limite);

    fetch("/TDG/AJAX/lista_playing", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Juegos", data.juegos);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTablaLista(mapRespuesta.get("Juegos"), 'playing');
        paginasPlaying(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

function paginacionBacklog(nPagina=null) {

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 6; // Número de juegos por página.
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

    // console.log(formData.get("filtros"));

    fetch("/TDG/AJAX/lista_backlog", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Juegos", data.juegos);

        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTablaLista(mapRespuesta.get("Juegos"), 'backlog');
        paginasBacklog(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

/* Buscamos el hidden que indica que se ha buscado por la busqueda y hacemos la busqueda con filtros, en caso de que no este hacemos una busqueda normal */
let busquedaHeader=document.getElementById("hiddenBusqueda");


paginacionWishlist();
paginacionCompleted();
paginacionPlaying();
paginacionBacklog();