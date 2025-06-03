"use strict";

/**
 * Asigna eventos para la gestión de datos CRUD y modales según la entidad seleccionada.
 *
 * Eventos incluidos:
 * - Click en elementos con clase "eliminar-dato" para eliminar un dato y recargar la tabla.
 * - Click en #btn_crear_dato para abrir modal de creación, limpiar campos y ajustar visibilidad.
 * - Click en elementos con clase "modificar-dato" para abrir modal de modificación y cargar datos.
 * - Click en #btn_crear para enviar datos nuevos al servidor y recargar tabla.
 * - Click en #btn_modificar para enviar datos modificados al servidor y recargar tabla.
 * - Click en #btn_cerrar_modal para cerrar y limpiar el modal de creación/modificación.
 * - Keyup en #busqueda para filtrar la tabla según texto ingresado.
 * - Para entidad "usuarios":
 *    - Click en elementos con clase "cambiarPassword" para abrir modal de cambio de contraseña.
 *    - Click en #btn_cambiarPass para validar y enviar cambio de contraseña.
 *    - Click en #btn_cerrar_modal_pass para cerrar y limpiar modal de cambio de contraseña.
 * - Para entidad "juegos":
 *    - Click en elementos con clase "addGenPlat" para abrir modal de selección de géneros y plataformas.
 */
function eventos() { // Cambiar data de los fetches.
    const entidad = document.getElementById("entidad").value;

    const modalCreacionModificacion = new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));

    document.addEventListener("DOMContentLoaded", () => {

    // Delegar click para botones Eliminar
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("eliminar-dato")) {
        const id = e.target.id.split("@")[1];
        

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
                backdrop: false,
                background: "#2C2C2E",
                color: "#FFFFFF"
            });
            paginacion(); // Recargar la tabla después de eliminar

            } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error en el servidor",
                showConfirmButton: false,
                timer: 1500,
                backdrop: false,
                background: "#2C2C2E",
                color: "#FFFFFF"
            });
            }
        });
        }
    });

    // Delegar click para botones Crear
    document.getElementById("btn_crear_dato").addEventListener("click", () => {
        modalCreacionModificacion.show();

        // Limpiar campos del modal
        document.querySelectorAll("[id$='Input']").forEach(input => {
            input.value = "";
        });

        document.getElementById("btn_modificar").style.display = "none"; // Ocultar botón de modificar
        document.getElementById("btn_crear").style.display = "block"; // Mostrar botón de crear

        document.getElementById("creacion-dato-header").style.display = "block"; // Mostrar el header de creación
        document.getElementById("modificacion-dato-header").style.display = "none"; // Ocultar el header de modificación
    });

    // Delegar click para botones Modificar
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("modificar-dato")) {
            const id = e.target.id.split("@")[1];

            document.getElementById("btn_modificar").style.display = "block"; // Ocultar botón de modificar
            document.getElementById("btn_crear").style.display = "none"; // Mostrar botón de crear

            document.getElementById("creacion-dato-header").style.display = "none"; // Mostrar el header de creación
            document.getElementById("modificacion-dato-header").style.display = "block"; // Ocultar el header de modificación

            modalCreacionModificacion.show();

            fetch("/TDG/AJAX/datosModificarDato", {
                method: "POST",
                headers: {
                "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({ id, entidad })
            })
            .then(res => res.text())
            .then(data => {
                const json = JSON.parse(data);
                const datos = json["dato"];

                for (let key in datos) {
                    const input = document.getElementById(key + "Input");
                    if (input) input.value = datos[key];
                    if (key === "Admin" || key === "Premium") {
                        const checkbox = document.getElementById(key + "Input");
                        if (datos[key] === "1") {
                            checkbox.checked=true; // Marcar el checkbox si el valor es "1"
                        }
                    }
                }
            });
        }
    });

    document.getElementById("btn_crear").addEventListener("click", () => {
        const datos = {};

        document.querySelectorAll("[id$='Input']").forEach(input => {

            const key = input.id.replace("Input", "");
            if(key == "Admin"){
                if(input.checked){
                    datos[key] = "1"; // Si el checkbox está marcado, asignar "1"
                }else{
                    datos[key] = "0"; // Si no está marcado, asignar "0"
                }
            }

            if(key == "Premium"){
                if(input.checked){
                    datos[key] = "1"; // Si el checkbox está marcado, asignar "1"
                }else{
                    datos[key] = "0"; // Si no está marcado, asignar "0"
                }
            }

            datos[key] = input.value;
        });

        const formData = new FormData();
        formData.append("datos", JSON.stringify(datos));
        formData.append("entidad", entidad);

        fetch("/TDG/AJAX/addDato", {
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
            backdrop: false,
            background: "#2C2C2E",
            color: "#FFFFFF"
        });
        paginacion(); // Recargar la tabla después de crear
        })
        .catch(() => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Error en el servidor.",
            showConfirmButton: false,
            timer: 3000,
            backdrop: false,
            background: "#2C2C2E",
            color: "#FFFFFF"
        });
        });
    });

    //Confirmar modificación
    document.getElementById("btn_modificar").addEventListener("click", () => {
        const datos = {};

        document.querySelectorAll("[id$='Input']").forEach(input => {
            const key = input.id.replace("Input", "");
            datos[key] = input.value;

            if(key == "Admin"){
                if(input.checked){
                    datos[key] = "1"; // Si el checkbox está marcado, asignar "1"
                }else{
                    datos[key] = "0"; // Si no está marcado, asignar "0"
                }
            }

            if(key == "Premium"){
                if(input.checked){
                    datos[key] = "1"; // Si el checkbox está marcado, asignar "1"
                }else{
                    datos[key] = "0"; // Si no está marcado, asignar "0"
                }
            }
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
            backdrop: false,
            background: "#2C2C2E",
            color: "#FFFFFF"
        });
        paginacion(); // Recargar la tabla después de modificar
        })
        .catch(() => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Error en el servidor",
            showConfirmButton: false,
            timer: 1500,
            backdrop: false,
            background: "#2C2C2E",
            color: "#FFFFFF"
        });
        });
    });

    // Cerrar y limpiar modal
    document.getElementById("btn_cerrar_modal").addEventListener("click", () => {
        document.querySelectorAll("[id$='Input']").forEach(input => {
        input.value = "";
        });

        if (modalCreacionModificacion) modalCreacionModificacion.hide();
    });

    });

    document.getElementById("busqueda").addEventListener("keyup", () => {

        let busqueda = document.getElementById("busqueda").value;

        paginacion(undefined, busqueda);
    });

    if(entidad=="usuarios"){
        const modalPass = new bootstrap.Modal(document.getElementById("modificacionPass"));
        document.addEventListener("click", function (e) {

        if (e.target.classList.contains("cambiarPassword")) {
            let id = e.target.id.split("@")[1];
            let id_usuarioinput=document.getElementById("id_usuario_pass");
            id_usuarioinput.value=id;

            let passInput=document.getElementById("contraseña_cambio");
            let confirmInput=document.getElementById("confirm_cambio");
            let error=document.getElementById("error_pass");

            error.textContent="";
            passInput.value="";
            confirmInput.value="";

            
            modalPass.show();
        }
        });

        document.getElementById("btn_cerrar_modal_pass").addEventListener("click", () => {

            let passInput=document.getElementById("contraseña_cambio");
            let confirmInput=document.getElementById("confirm_cambio");
            let error=document.getElementById("error_pass");

            error.textContent="";
            passInput.value="";
            confirmInput.value="";

            modalPass.hide();
        });

        document.getElementById("btn_cambiarPass").addEventListener("click", ()=>{

            let id_usuarioinput=document.getElementById("id_usuario_pass");
            let passInput=document.getElementById("contraseña_cambio");
            let confirmInput=document.getElementById("confirm_cambio");
            let error=document.getElementById("error_pass");

            let pass=passInput.value;
            let confirm=confirmInput.value;
            let id_usuario=id_usuarioinput.value;

            if(pass=="" || confirm==""){
                error.textContent="Se deben de completar todos los campos";
            }else if(pass!==confirm){
                error.textContent="Las contraseñas no coinciden";
            } else {
                let formData = new FormData();
                formData.append("Pass", pass);
                formData.append("id_usuario", id_usuario);

                fetch("/TDG/AJAX/cambiarPassAdmin", {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {

                        modalPass.hide();

                        if (data.result == "ok") {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Contraseña modificada con éxito",
                                showConfirmButton: false,
                                timer: 1500,
                                backdrop: false,
                                background: "#2C2C2E",
                                color: "#FFFFFF"
                            });
                            paginacion(); // Recargar la tabla después de crear
                        } else {
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: "Error en el servidor",
                                showConfirmButton: false,
                                timer: 1500,
                                backdrop: false,
                                background: "#2C2C2E",
                                color: "#FFFFFF"
                            });
                        }
                    });
            }
        });
    }

    if(entidad=="juegos"){
        const modalGenPlat = new bootstrap.Modal(document.getElementById("modificacionGeneroPlat"));
        document.addEventListener("click", function (e) {

        if (e.target.classList.contains("addGenPlat")) {
            let id = e.target.id.split("@")[1];
            let id_juegoinput=document.getElementById("id_usuario_GenPlat");
            id_juegoinput.value=id;
            
            let checksGeneros=document.getElementsByClassName("generoCheck");
            let checksPlataformas=document.getElementsByClassName("plataformaCheck");

            let formData= new FormData();
            formData.append("id_juego", id)

            fetch("/TDG/AJAX/getGenPlatJuegoAdmin",{
                method: "POST",
                body:formData
            })
            .then(res=>res.json())
            .then(data=>{
                let generosSeleccionados=data.generos;
                let plataformasSeleccionadas=data.plataformas;

                for(let genero of checksGeneros){

                    generosSeleccionados.forEach(id=>{
                        if(genero.value==id.id){
                            genero.checked=true;
                        }
                    });
                    
                }

                for(let plataforma of checksPlataformas){

                    plataformasSeleccionadas.forEach(id=>{
                        if(plataforma.value==id.id){
                            plataforma.checked=true;
                        }
                    });
                    
                }

                modalGenPlat.show();
            }).catch(error=>{
                console.log(error);
            })

           
        }
        });
    }

    
}

/**
 * Crea y muestra una tabla con paginación para escritorio y móvil a partir de una lista de datos.
 *
 * @param {Array<Object>} lista - Lista de objetos con los datos a mostrar.
 * @param {Array<string>} columnas - Array con los nombres de las columnas a mostrar.
 * @param {string} entidad - Nombre de la entidad actual ("usuarios", "juegos", etc.) para personalizar acciones.
 */
function crearTabla(lista, columnas, entidad) {

    let list_juegos=document.getElementById("tabla-datos");
    list_juegos.innerHTML='';

    //Tabla Escritorio

    // Generar encabezado
    const thead = document.createElement("thead");
    const trHead = document.createElement("tr");

    columnas.forEach(columna => {
        if (columna === "id"){
            
        }else if (columna === "Password") {

        }else {
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
            if (key === "Password") continue; // omitir Password en las columnas si no se desea mostrarlo directamente
            
            let campo = item[key];
            if (campo.length >= 20) {
                campo = campo.substring(0, 20) + "...";
            }
            
            if(key=="Imagen"){
                const td = document.createElement("td");
                const enlace = document.createElement('a');
                enlace.className = "sub";
                enlace.href = item[key];
                enlace.target = "_blank"; // Abrir en nueva pestaña
                enlace.textContent = campo;
                td.appendChild(enlace);
                tr.appendChild(td);
            }else if(key=="Admin" || key =="Premium"){
                if(campo==1){
                    const td = document.createElement("td");
                    const i= document.createElement("i");

                    i.classList.add("fa-regular", "fa-square-check", "text-success");
                    td.append(i);

                    td.title = item[key];
                    tr.appendChild(td);
                }else{
                    const td = document.createElement("td");
                    const i= document.createElement("i");

                    i.classList.add("fa-solid", "fa-square-xmark", "text-danger");
                    td.append(i);

                    td.title = item[key];
                    tr.appendChild(td);
                }
            }
            else{
                const td = document.createElement("td");
                td.textContent = campo;
                td.title = item[key];
                tr.appendChild(td);
            }
        }

        // Celda de acciones
        const tdAcciones = document.createElement("td");
        tdAcciones.innerHTML = `
            <div class="dropdown">
                <button class="boton-perso boton-perso-secundario dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones
                </button>
                <div class="dropdown-menu">
                <button id="btn_eliminar@${item.id}" class="dropdown-item btn btn-danger eliminar-dato">Eliminar</button>
                <button id="btn_modificar@${item.id}" class="dropdown-item btn btn-primary modificar-dato">Modificar</button>
                ${entidad === "usuarios" ? `<button id="btn_cambPass@${item.id}" class="dropdown-item btn btn-primary cambiarPassword">Cambiar Contraseña</button>` : ''}
                ${entidad === "juegos" ? `<button id="add_genPlat@${item.id}" class="dropdown-item btn btn-primary addGenPlat">Añadir/Modificar Generos y Plataformas</button>` : ''}
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
      let campo;
      if(campoOriginal.length >= 20){
        campo=campoOriginal.substring(0, 20) + "..."
      }else{
        campo=campoOriginal;
      }

      const columna = key;

      const divColumna = document.createElement("div");
      divColumna.classList.add("columna");

      if(columna !== "id"){
        
      }
      const header = document.createElement("div");
      header.classList.add("header");
      header.textContent = columna;

      const contenido = document.createElement("div");
      contenido.classList.add("contenido");
      if(key === "Imagen"){
        const enlace = document.createElement('a');
          enlace.className = "sub";
          enlace.href = campoOriginal;
          enlace.target = "_blank"; // Abrir en nueva pestaña
          enlace.textContent = campo;
          contenido.appendChild(enlace);
      }else{
        contenido.textContent = campo;
      }

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
      const btnPass = document.createElement("button");
      btnPass.className = "dropdown-item btn btn-primary cambiarPassword";
      btnPass.textContent = "Cambiar Contraseña";
      btnPass.id=`btn_cambPass@${item.id}`;
      dropdownMenu.appendChild(btnPass);
    }

    if (entidad === "juegos") {
      const btnGenPlat = document.createElement("button");
      btnGenPlat.className = "dropdown-item btn btn-primary addGenPlat";
      btnGenPlat.textContent = "Añadir/Modificar Generos y Plataformas";
      btnGenPlat.id=`add_genPlat@${item.id}`;
      dropdownMenu.appendChild(btnGenPlat);
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


/**
 * Muestra una barra de paginación con botones para navegar entre páginas.
 * 
 * @param {number} pagina - Página actual activa.
 * @param {number} total_paginas - Número total de páginas disponibles.
 */
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

/**
 * Maneja la paginación y búsqueda de datos, realiza petición AJAX para obtener datos y actualiza la tabla y paginación.
 *
 * @param {number|null} [nPagina=null] - Número de página a mostrar (por defecto la 1).
 * @param {string|null} [busqueda=null] - Texto para filtrar los datos (opcional).
 */
function paginacion(nPagina=null, busqueda=null) {

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
    if (busqueda) {
        formData.append("busqueda", busqueda);
    }
    

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