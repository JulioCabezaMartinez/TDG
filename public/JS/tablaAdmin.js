"use strict";

/* Eventos click de Click de la Página */

function eventos() { // Cambiar data de los fetches.

    document.addEventListener("DOMContentLoaded", () => {

    // Delegar click para botones Eliminar
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("eliminar-dato")) {
        const id = e.target.id.split("@")[1];
        const entidad = document.getElementById("entidad").value;

        fetch("/TDG/AJAX/eliminarDato", {
            method: "POST",
            headers: {
            "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({ id, entidad })
        })
        .then(res => res.text())
        .then(data => {
            if (data === "Todo Correcto") {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Dato eliminado con éxito",
                showConfirmButton: false,
                timer: 1500,
                backdrop: false
            });
            } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error en el servidor",
                showConfirmButton: false,
                timer: 1500,
                backdrop: false
            });
            }
        });
        }
    });

    // Delegar click para botones Modificar
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("modificar-dato")) {
        const id = e.target.id.split("@")[1];
        const entidad = document.getElementById("entidad").value;

        const modal = new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));
        modal.show();

        fetch("/TDG/AJAX/datosModificarDato", {
            method: "POST",
            headers: {
            "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({ id, entidad })
        })
        .then(res => res.text())
        .then(data => {
            console.log(data);
            const json = JSON.parse(data);
            const datos = json["dato"];

            for (let key in datos) {
            const input = document.getElementById(key + "Input");
            if (input) input.value = datos[key];
            }
        });
        }
    });

    //onfirmar modificación
    document.getElementById("btn_modificar").addEventListener("click", () => {
        const entidad = document.getElementById("entidad").value;
        const datos = {};

        document.querySelectorAll("[id$='Input']").forEach(input => {
        const key = input.id.replace("Input", "");
        datos[key] = input.value;
        });

        const formData = new FormData();
        formData.append("datos", JSON.stringify(datos));
        formData.append("entidad", entidad);

        fetch("/TDG/AJAX/modificarDato", {
        method: "POST",
        body: formData
        })
        .then(res => res.text())
        .then(() => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Dato modificado con éxito",
            showConfirmButton: false,
            timer: 1500,
            backdrop: false
        });
        })
        .catch(() => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Error en el servidor",
            showConfirmButton: false,
            timer: 1500,
            backdrop: false
        });
        });
    });

    // Cerrar y limpiar modal
    document.getElementById("btn_cerrar_modal").addEventListener("click", () => {
        document.querySelectorAll("[id$='Input']").forEach(input => {
        input.value = "";
        });

        const modal = bootstrap.Modal.getInstance(document.getElementById("creacion_modificar_dato"));
        if (modal) modal.hide();
    });

    });
}

/* Paginación de listas */
function crearTabla(lista, columnas, entidad) {

    let list_juegos=document.getElementById("tabla-datos");
    list_juegos.innerHTML='';

    //Tabla Escritorio

    // Generar encabezado
    const thead = document.createElement("thead");
    const trHead = document.createElement("tr");

    columnas.forEach(columna => {
        if (columna === "id"){
            
        }else{
            const th = document.createElement("th");
            th.textContent = columna;
            trHead.appendChild(th);
        }
    });

    const thAcciones = document.createElement("th");
    thAcciones.textContent = "Acciones";
    trHead.appendChild(thAcciones);

    thead.appendChild(trHead);
    list_juegos.appendChild(thead);

    // Generar cuerpo
    const tbody = document.createElement("tbody");

    lista.forEach(item => {
        const tr = document.createElement("tr");

        for (const key in item) {
            if (key === "id") continue; // omitir id en las columnas si no se desea mostrarlo directamente
            let campo = item[key];
            if (typeof campo === "string" && campo.length >= 20) {
                campo = campo.substring(0, 20) + "...";
            }
            const td = document.createElement("td");
            td.textContent = campo;
            tr.appendChild(td);
        }

        // Celda de acciones
        const tdAcciones = document.createElement("td");
        tdAcciones.innerHTML = `
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones
                </button>
                <div class="dropdown-menu">
                <button id="btn@${item.id}" class="dropdown-item btn btn-danger eliminar-dato">Eliminar</button>
                <button id="btn@${item.id}" class="dropdown-item btn btn-primary modificar-dato">Modificar</button>
                ${entidad === "usuarios" ? '<button class="dropdown-item btn btn-primary">Ver Listas</button>' : ''}
                ${entidad === "usuarios" ? '<button class="dropdown-item btn btn-primary">Cambiar Contraseña</button>' : ''}
                </div>
            </div>
            `;
        tr.appendChild(tdAcciones);

        tbody.appendChild(tr);
        list_juegos.appendChild(tbody);

    });

    // Tabla Móvil
    let tabla_movil = document.getElementById("tabla-movil");
    tabla_movil.innerHTML = '';

    lista.forEach(item => {
    const fila = document.createElement("div");
    fila.classList.add("fila");

    let contador = 0;
    for (const key in item) {
      if (key === "id") continue; // omitir si no quieres mostrar 'id'

      const campoOriginal = item[key];
      let campo = typeof campoOriginal === "string" && campoOriginal.length >= 20
        ? campoOriginal.substring(0, 20) + "..."
        : campoOriginal;

      const columna = columnas[contador] || key;

      const divColumna = document.createElement("div");
      divColumna.classList.add("columna");

      const header = document.createElement("div");
      header.classList.add("header");
      header.textContent = columna;

      const contenido = document.createElement("div");
      contenido.classList.add("contenido");
      contenido.textContent = campo.replace(/(<([^>]+)>)/gi, ""); // strip HTML tags

      divColumna.appendChild(header);
      divColumna.appendChild(contenido);
      fila.appendChild(divColumna);

      contador++;
    }

    // Columna de acciones

    const columnaFinal = document.createElement("div");
    columnaFinal.classList.add("columna", "columna-final");

    const headerAcciones = document.createElement("div");
    headerAcciones.classList.add("header");
    headerAcciones.textContent = "Acciones";

    const contenidoAcciones = document.createElement("div");
    contenidoAcciones.classList.add("contenido");

    const dropdown = document.createElement("div");
    dropdown.classList.add("dropdown");

    const btnToggle = document.createElement("button");
    btnToggle.className = "btn btn-secondary dropdown-toggle";
    btnToggle.type = "button";
    btnToggle.setAttribute("data-toggle", "dropdown");
    btnToggle.setAttribute("aria-haspopup", "true");
    btnToggle.setAttribute("aria-expanded", "false");
    btnToggle.textContent = "Acciones";

    const dropdownMenu = document.createElement("div");
    dropdownMenu.classList.add("dropdown-menu");

    const btnEliminar = document.createElement("button");
    btnEliminar.id = `btnEliminar@${item.id}`;
    btnEliminar.className = "dropdown-item btn btn-danger eliminar-dato";
    btnEliminar.textContent = "Eliminar";

    const btnModificar = document.createElement("button");
    btnModificar.id = `btnModificar@${item.id}`;
    btnModificar.className = "dropdown-item btn btn-primary modificar-dato";
    btnModificar.textContent = "Modificar";

    dropdownMenu.appendChild(btnEliminar);
    dropdownMenu.appendChild(btnModificar);

    if (entidad === "usuarios") {
      const btnListas = document.createElement("button");
      btnListas.className = "dropdown-item btn btn-primary";
      btnListas.textContent = "Ver Listas";
      dropdownMenu.appendChild(btnListas);
    }

    dropdown.appendChild(btnToggle);
    dropdown.appendChild(dropdownMenu);
    contenidoAcciones.appendChild(dropdown);
    columnaFinal.appendChild(headerAcciones);
    columnaFinal.appendChild(contenidoAcciones);
    fila.appendChild(columnaFinal);

    tabla_movil.appendChild(fila);
  });
}

/* Permite ver una paginación con todas las páginas que va a tener la página */
function paginas(pagina, total_paginas){

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
                paginacion(i); // Llamada a la función
            });

            li.appendChild(a);
            ul.appendChild(li);
        }

        nav.appendChild(ul);

        contenedorPag.appendChild(nav);
    }
}

function paginacion(nPagina=null, filtros={}) {

    let entidad = document.getElementById("entidad").value; // Obtener la entidad desde el input oculto. 

    let pagina = nPagina ?? 1; // Obtener la página actual desde parametro.

    let limite = 5; // Número de juegos por página.
    let inicio = 0;

    if (pagina <= 0) {
        pagina = 1;
    } else {
        inicio = (pagina - 1) * limite; // 5 juegos por página.
    }

    // Body del AJAX.
    let formData = new FormData();
    formData.append("entidad", entidad);
    formData.append("pagina", pagina);
    formData.append("inicio", inicio);
    formData.append("limite", limite);

    fetch("/TDG/AJAX/lista_Admin", {
        method: "POST",
        body: formData

    }).then(response => response.json())
    .then(data => {
        let mapRespuesta=new Map();
        mapRespuesta.set("Datos", data.datos);
        mapRespuesta.set("Pagina", data.pagina);
        mapRespuesta.set("Total_paginas", data.total_paginas);
        mapRespuesta.set("Columnas", data.columnas);

        crearTabla(mapRespuesta.get("Datos"), mapRespuesta.get("Columnas"), entidad);
        paginas(mapRespuesta.get("Pagina"), mapRespuesta.get("Total_paginas"));

    }).catch(err => console.log(err));
}

paginacion();
eventos();