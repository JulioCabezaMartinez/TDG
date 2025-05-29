"use strict";

function buscarFiltros() {
    let filtros={};
    document.querySelectorAll('[id$="Input"]').forEach(function (input) {
        let valor = input.value;
        let clave = input.id.replace('Input', '');

        if (valor) {
            if (clave == "nombre") {
                filtros[clave] = "%" + valor + "%";

            }else if(clave == "calificacion"){
                if(valor>5){
                    input.value="5";
                    valor=5;
                    filtros[clave] = valor;
                
                } else if(valor<= 0){
                    input.value="1";
                    valor=1;
                    filtros[clave] = valor;
                }
            }else {
                filtros[clave] = valor;
            }
        }
    })

    return filtros;
}

/* Eventos click de Click de la Página */
function eventos() {
    document.getElementById("boton_filtro").addEventListener("click", function () {

        document.querySelectorAll(".filtros_desplegable").forEach(function (el) {
            el.classList.toggle("active");
        });
    });

    // Aplicar Filtros
    document.getElementById("aplicarFiltros").addEventListener("click", function(){
        let filtros=buscarFiltros();

        console.log(filtros);

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
function crearTabla(juegos, sesion=undefined) {
    console.log(sesion);
    let list_juegos=document.getElementById("list_juegos");
    list_juegos.innerHTML='';

    const iconos = [
        ['wish', 'heart'],
        ['back', 'clock'],
        ['comp', 'circle-check'],
        ['play', 'circle-play']
    ];



    juegos.forEach(juego => {
        // Crear contenedor principal
        const divJuego = document.createElement('div');
        divJuego.className = 'juego';

        // Imagen
        const img = document.createElement('img');
        img.src = juego.Imagen;
        img.alt = '';
        divJuego.appendChild(img);

        // Info del juego
        const infoJuego = document.createElement('div');
        infoJuego.className = 'info_juego';

        // Enlace y título
        const enlace = document.createElement('a');
        enlace.className = 'enlace_juego';
        enlace.href = '/TDG/juegos/view?juego=' + juego.id;

        const h1 = document.createElement('h1');
        h1.textContent = juego.Nombre;

        enlace.appendChild(h1);
        infoJuego.appendChild(enlace);

        // Calificación
        const pCal = document.createElement('p');
        pCal.innerHTML = `<strong>Calificación:</strong> ${juego.calificacion} / 5`;
        infoJuego.appendChild(pCal);

        /* Intenté hacerlo de esta manera pero el textContent de pCal sobreescribia el strong */

        // const strongCal=document.createElement('strong');
        // strongCal.textContent= "Calificación:";
        // pCal.appendChild(strongCal);
        // pCal.textContent=`${juego.calificacion} / 5`;

        // Año de salida
        const pFecha = document.createElement('p');
        pFecha.innerHTML = `<strong>Fecha de salida:</strong> ${juego.Anyo_salida}`;
        infoJuego.appendChild(pFecha);

        const btnListas = document.createElement('div');

        if(sesion !== undefined){
            // Botones/íconos
            
            btnListas.className = 'btn_listas';

            iconos.forEach(([prefix, icono], i) => {
                const tipo = juego.estados[i] ? 'fa-solid' : 'fa-regular';
                const iElem = document.createElement('i');
                iElem.id = `${prefix}@${juego.id}`;
                iElem.className = `icono ${tipo} fa-${icono}`;
                btnListas.appendChild(iElem);
            });

            
        }

        divJuego.appendChild(infoJuego);
        if(btnListas!== undefined){
            divJuego.appendChild(btnListas);
        }

        list_juegos.appendChild(divJuego);
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
    formData.append("filtros", JSON.stringify(filtros));

    // console.log(formData.get("filtros"));

    fetch("/TDG/AJAX/lista_juegos", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Juegos", data.juegos);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);
        mapRespuesta.set("Sesion", data.sesion);

        crearTabla(mapRespuesta.get("Juegos"), mapRespuesta.get("Sesion"));
        paginas(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

/* Buscamos el hidden que indica que se ha buscado por la busqueda y hacemos la busqueda con filtros, en caso de que no este hacemos una busqueda normal */
let busquedaHeader=document.getElementById("hiddenBusqueda");

if(busquedaHeader){
    
    let busqueda=document.getElementById("busquedaJuego");
    busqueda.value=busquedaHeader.value;

    let filtros=buscarFiltros();
    paginacion(undefined, filtros);
}else{
    paginacion();
}
eventos();