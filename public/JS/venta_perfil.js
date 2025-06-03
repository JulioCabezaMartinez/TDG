
/**
 * Crea y muestra una lista de ventas en el DOM.
 *
 * @param {Array<Object>} ventas - Array con objetos de ventas a mostrar.
 */
function crearTablaVentas(ventas) {
   
    $lista_ventas = document.querySelector("#lista_ventas");
    $lista_ventas.innerHTML = ''; // Limpiar la lista antes de añadir nuevas ventas

    ventas.forEach(venta => {
        // Asignar imagen por defecto si no hay
        if (!venta.Imagen) {
            venta.Imagen = "/TDG/public/IMG/default-game.jpg";
        }

        // Crear elementos
        const div = document.createElement("div");
        div.className = "venta";

        const link = document.createElement("a");
        if(venta.id != -1){
            link.href = `/TDG/ventas/view?id=${venta.id}`;
        }

        const img = document.createElement("img");
        img.src = venta.Imagen;

        const h4 = document.createElement("h4");
        h4.className = "nombreVenta";
        h4.textContent = venta.Titulo;

        // Ensamblar estructura
        link.appendChild(img);
        link.appendChild(h4);
        div.appendChild(link);

        // Finalmente, añadirlo al DOM, por ejemplo a un contenedor existente
        document.querySelector("#lista_ventas").appendChild(div);
    });
}

/**
 * Crea y muestra una lista de compras en el DOM.
 *
 * @param {Array<Object>} compras - Array con objetos de compras a mostrar.
 */
function crearTablaCompras(compras) {

    $lista_compras = document.querySelector("#lista_compras");
    $lista_compras.innerHTML = ''; // Limpiar la lista antes de añadir nuevas compras

    compras.forEach(compra => {
        // Asignar imagen por defecto si no hay
        if (!compra.Imagen) {
            compra.Imagen = "/TDG/public/IMG/default-game.jpg";
        }

        // Crear elementos
        const div = document.createElement("div");
        div.className = "compra";

        const link = document.createElement("a");
        if(compra.Producto.id != -1){
            link.href = `/TDG/ventas/view?id=${compra.Producto.id}`;
        }

        const img = document.createElement("img");
        img.src = compra.Imagen;

        const h4 = document.createElement("h4");
        h4.className = "nombreCompra";
        h4.textContent = compra.Producto.Titulo;

        // Ensamblar estructura
        link.appendChild(img);
        link.appendChild(h4);
        div.appendChild(link);

        // Finalmente, añadirlo al DOM, por ejemplo a un contenedor existente
        document.querySelector("#lista_compras").appendChild(div);
    });
}

/**
 * Muestra una barra de paginación para las compras con números de página.
 *
 * @param {number} pagina - Página actual activa.
 * @param {number} total_paginas - Total de páginas disponibles.
 */
function paginasCompras(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionCompras");

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

/**
 * Muestra una barra de paginación para las ventas con números de página.
 *
 * @param {number} pagina - Página actual activa.
 * @param {number} total_paginas - Total de páginas disponibles.
 */
function paginasVentas(pagina, total_paginas){

    const paginas= document.getElementsByClassName("paginacionVentas");

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

/**
 * Maneja la paginación de ventas, realiza petición AJAX para obtener datos y actualiza la lista y paginación.
 *
 * @param {number|null} [nPagina=null] - Página a mostrar (por defecto la 1).
 */
function paginacionVentas(nPagina=null) {

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

    fetch("/TDG/AJAX/lista_ventas_perfil", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Ventas", data.ventas);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);
        mapRespuesta.set("Sesion", data.sesion);

        crearTablaVentas(mapRespuesta.get("Ventas"));
        paginasVentas(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

/**
 * Maneja la paginación de compras, realiza petición AJAX para obtener datos y actualiza la lista y paginación.
 *
 * @param {number|null} [nPagina=null] - Página a mostrar (por defecto la 1).
 */
function paginacionCompras(nPagina=null) {

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

    fetch("/TDG/AJAX/lista_compras_perfil", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Compras", data.compras);

        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);

        crearTablaCompras(mapRespuesta.get("Compras"));
        paginasCompras(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

/* Buscamos el hidden que indica que se ha buscado por la busqueda y hacemos la busqueda con filtros, en caso de que no este hacemos una busqueda normal */
let busquedaHeader=document.getElementById("hiddenBusqueda");


paginacionVentas();
paginacionCompras();

eventos();