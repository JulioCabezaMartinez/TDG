"use strict";

/**
 * Recoge los valores de los filtros en la interfaz y devuelve un objeto con esos filtros.
 * - Para el filtro "nombre", añade % para búsqueda tipo LIKE.
 * - Recoge el valor seleccionado de los radio buttons con nombre "stock".
 * @returns {Object} filtros - Objeto con los filtros activos.
 */
function buscarFiltros() {
    let filtros={};
    document.querySelectorAll('[id$="InputFiltro"]').forEach(function (input) {
        let valor = input.value;
        let clave = input.id.replace('InputFiltro', '');

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

/**
 * Añade los event listeners para la interacción con la página:
 * - Mostrar/ocultar filtros desplegables.
 * - Aplicar filtros.
 * - Resetear filtros.
 * - Abrir modal para crear un producto.
 * - Registrar un nuevo producto mediante AJAX.
 */
function eventos() {

    const modal_creacion=new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));

    document.getElementById("boton_filtro").addEventListener("click", function () {

        document.querySelectorAll(".filtros_desplegable").forEach(function (el) {
            el.classList.toggle("active");
        });
    });

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

    document.getElementById("btn_crear_producto").addEventListener("click", function () {
        const modal = new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));
        modal.show();

        // Limpiar campos del modal
        // document.querySelectorAll("[id$='Input']").forEach(input => {
        //     input.value = "";
        // });
        
    });

    document.getElementById("btn_crear").addEventListener("click", () => {
        const datos = {};
        let img="";

        document.querySelectorAll("[id$='Input']").forEach(input => {

            const key = input.id.replace("Input", "");

            if(key=="img_venta"){
                if(input.files.length > 0){
                    img = input.files[0];
                }
            }else{
                datos[key] = input.value;
            }
        });

        let vacio=false;

        for( let [key, dato] of Object.entries(datos) ){
                if(dato.trim() == ""){
                    vacio=true;
                }
        }

        if(vacio){

            let error_global=document.getElementById("error_global");
            error_global.textContent="Mandatory data must be completed";

        }else{
            const formData = new FormData();
            formData.append("datos", JSON.stringify(datos));
            if(img !== ""){
                formData.append("img", img);
            }

            fetch("/AJAX/registrarProducto", {
            method: "POST",
            body: formData
            })
            .then(res => res.json())
            .then((data) => {
                if(data.result=="ok"){
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Product successfully registered",
                        showConfirmButton: false,
                        timer: 1500,
                        backdrop: false,
                        background: "#2C2C2E",
                        color: "#FFFFFF"
                    });
                }else{
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: data.mensaje,
                        showConfirmButton: false,
                        timer: 1500,
                        backdrop: false,
                        background: "#2C2C2E",
                        color: "#FFFFFF"
                    });
                }

                modal_creacion.hide();
                paginacion();
            })
            .catch(() => {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Server error",
                    showConfirmButton: false,
                    timer: 1500,
                    backdrop: false,
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
                modal_creacion.hide();
            });
        }

        
    });
}

/**
 * Genera la tabla/lista de ventas que se muestran en la página.
 * Cada venta se muestra como un enlace que lleva a la vista detallada.
 * @param {Array} ventas - Lista de objetos con la información de cada venta.
 */
function crearTabla(ventas) {
    let lista_ventas=document.getElementById("lista_ventas");
    lista_ventas.innerHTML='';

    // Recorremos la lista de ventas y generamos los elementos HTML
    ventas.forEach(venta => {
        // Crear el enlace
        const link = document.createElement('a');
        link.href = `/ventas/view?id=${venta.id}`;

        // Crear el div de la venta
        const ventaDiv = document.createElement('div');
        ventaDiv.id = `${venta.id}`;
        ventaDiv.classList.add('venta');

        // Crear la imagen
        const img = document.createElement('img');
        img.src = `/public/IMG/Productos-img/${venta.img_venta}`;

        // Crear la sección de información del juego
        const infoJuegoDiv = document.createElement('div');
        infoJuegoDiv.classList.add('info_juego');

        // Crear el título
        const tituloH1 = document.createElement('h2');
        tituloH1.classList.add('titulo');
        if( venta.Titulo.length > 10) {
            tituloH1.textContent = venta.Titulo.slice(0, 10) + '...';
            tituloH1.title = venta.Titulo; // Añadir el título completo como atributo title
        }else{
            tituloH1.textContent = venta.Titulo;
        }    
        

        // Crear el precio
        const precioP = document.createElement('p');
        precioP.classList.add('precio');
        const precioStrong = document.createElement('strong');
        precioStrong.textContent = `${venta.Precio}€`;
        precioP.appendChild(precioStrong);

        // Crear el Estado
        const estadoP = document.createElement('p');
        estadoP.classList.add('estado_producto');
        estadoP.innerHTML = `<strong>State:</strong> ${venta.Estado}`;

        // Crear la consola
        const consolaP = document.createElement('p');
        consolaP.classList.add('consola_producto');
        consolaP.innerHTML = `<strong>Platform:</strong> ${venta.Consola}`;
        

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

/**
 * Crea la paginación en base a la página actual y el total de páginas.
 * Muestra un rango limitado de páginas para facilitar la navegación.
 * @param {number} pagina - Página actual.
 * @param {number} total_paginas - Total de páginas disponibles.
 */
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

/**
 * Controla la lógica de paginación.
 * Realiza la petición AJAX para obtener la lista de ventas según página y filtros.
 * Actualiza la tabla y la paginación en la página.
 * @param {number|null} nPagina - Número de página a mostrar (por defecto 1).
 * @param {Object} filtros - Filtros a aplicar a la consulta (por defecto vacío).
 */
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

    fetch("/AJAX/lista_ventas", {
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