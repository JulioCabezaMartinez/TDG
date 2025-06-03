const expresiones_regulares=new Map();
expresiones_regulares.set("correo", /^[\w.-]+@[\w.-]+\.\w+$/);
expresiones_regulares.set("password_completa", /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/);
expresiones_regulares.set("password_mayusculas", /[A-Z]/);
expresiones_regulares.set("password_minusculas", /[a-z]/);
expresiones_regulares.set("password_numero", /\d/);
expresiones_regulares.set("password_especial", /[!@#$%^&*(),.?":{}|<>]/);

/**
 * Actualiza el contenido y la clase CSS de un elemento para reflejar un estado de validación.
 *
 * @param {string} id - El ID del elemento HTML a actualizar.
 * @param {boolean} esValido - Indica si el estado es válido (true) o inválido (false).
 * @param {string} mensaje - El mensaje de texto que se mostrará dentro del elemento.
 *
 */
function actualizarEstado(id, esValido, mensaje) {
    const elemento = document.getElementById(id);
    elemento.textContent = mensaje;
    elemento.classList.remove("text-success", "text-danger");
    elemento.classList.add(esValido ? "text-success" : "text-danger");
}

/**
 * Asigna eventos a botones relacionados con filtros y paginación.
 *
 * Eventos incluidos:
 * - Click en elementos con clase "cambiarPassword" para abrir el modal de cambio de contraseña y limpiar campos.
 * - Click en elementos con clase "modificar-dato" para abrir el modal de modificación de datos y cargar datos del usuario.
 * - Click en #btn_modificar para enviar datos modificados del usuario al servidor.
 * - Click en #btn_cerrar_modal para cerrar y limpiar el modal de modificación de datos.
 * - Click en #btn_cambiarPass para validar y enviar cambio de contraseña al servidor.
 * - Input en #contraseña_cambio para validar la fortaleza de la contraseña y actualizar barra de progreso.
 * - Click en #btn_cerrar_modal_pass para cerrar y limpiar el modal de cambio de contraseña.
 */
function eventos(){

    const modalCreacionModificacion = new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));
    const modalPass = new bootstrap.Modal(document.getElementById("modificacionPass"));

    //Abrir Modal de Cambio de Contraseña
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
    //Abrir Modal de Modificación de Datos
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("modificar-dato")) {
            let id = e.target.id.split("@")[1];
            let entidad = "usuarios";

            modalCreacionModificacion.show();

            formData = new FormData();
            formData.append("id", id);
            formData.append("entidad", entidad);

            fetch("/TDG/AJAX/datosModificarDato", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                const json = JSON.parse(data);
                const datos = json["dato"];

                for (let key in datos) {
                    const input = document.getElementById(key + "Input");
                    if (input) input.value = datos[key];
                }
            });
        }
    });

    // Enviar Datos de Modificacion de Usuario
    document.getElementById("btn_modificar").addEventListener("click", () => {
        const entidad = "usuarios";
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
        .then(res => res.json())
        .then(data => {
            if(data.result=="ok"){
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Usuario modificado con éxito",
                    showConfirmButton: false,
                    timer: 1500,
                    backdrop: false,
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
                modalCreacionModificacion.hide();
            }else{
                Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Perfil no modificado",
                showConfirmButton: false,
                timer: 1500,
                backdrop: false,
                background: "#2C2C2E",
                color: "#FFFFFF"
            });
            }
            
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

    // Cerrar y limpiar Modal de Modificación de Datos
    document.getElementById("btn_cerrar_modal").addEventListener("click", () => {
        document.querySelectorAll("[id$='Input']").forEach(input => {
        input.value = "";
        });

        if (modalCreacionModificacion) modalCreacionModificacion.hide();
    });

    // Enviar Datos de Cambio de Pass
    document.getElementById("btn_cambiarPass").addEventListener("click", ()=>{
        let id_usuarioinput=document.getElementById("id_usuario_pass");
        let passActualInput=document.getElementById("contraseña_actual");
        let passInput=document.getElementById("contraseña_cambio");
        let confirmInput=document.getElementById("confirm_cambio");
        let error=document.getElementById("error_pass");

        let passActual=passActualInput.value;
        let pass=passInput.value;
        let confirm=confirmInput.value;
        let id_usuario=id_usuarioinput.value;

        let validacionPass=/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/;

        if(passActual=="" || pass=="" || confirm==""){
            error.textContent="Se deben de completar todos los campos";
        }else if(!validacionPass.test(pass)){
            error.textContent="La contraseña no es valida";
        }else if(pass!==confirm){
            error.textContent="Las contraseñas no coinciden";
        } else {
            let formDataPassActual=new FormData();
            formDataPassActual.append("passActual", passActual);

            // Comprobacion de contraseña Actual
            fetch("/TDG/AJAX/compruebaPass", {
                    method: "POST",
                    body: formDataPassActual
                })
                .then(res=>res.json())
                .then(data=>{

                    if (data.result == "ok") {
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
                            }).catch(error => {
                                console.log(error);
                            });
                    } else {
                        error.textContent = data.mensaje;
                    }
                })
                .catch(error => {
                    console.log(error);
                })
        }
    });

    //Validaciones de la contraseña
    document.getElementById("contraseña_cambio").addEventListener("input", function () {
        const password = this.value;
        let strength = 0;

        // Validaciones
        if (password.length >= 8) {
            actualizarEstado("length", true, "✅ Mínimo 8 caracteres");
            strength++;
        } else {
            actualizarEstado("length", false, "❌ Mínimo 8 caracteres");
        }

        if (expresiones_regulares.get("password_mayusculas").test(password)) {
            actualizarEstado("uppercase", true, "✅ Al menos una mayúscula");
            strength++;
        } else {
            actualizarEstado("uppercase", false, "❌ Al menos una mayúscula");
        }

        if (expresiones_regulares.get("password_minusculas").test(password)) {
            actualizarEstado("lowercase", true, "✅ Al menos una minúscula");
            strength++;
        } else {
            actualizarEstado("lowercase", false, "❌ Al menos una minúscula");
        }

        if (expresiones_regulares.get("password_numero").test(password)) {
            actualizarEstado("number", true, "✅ Al menos un número");
            strength++;
        } else {
            actualizarEstado("number", false, "❌ Al menos un número");
        }

        if (expresiones_regulares.get("password_especial").test(password)) {
            actualizarEstado("special", true, "✅ Al menos un carácter especial");
            strength++;
        } else {
            actualizarEstado("special", false, "❌ Al menos un carácter especial");
        }

        // Actualizar barra de progreso
        const percentage = (strength / 5) * 100;
        const bar = document.getElementById("password-strength-bar");
        bar.style.width = percentage + "%";

        // Colores según la seguridad
        bar.className = "progress-bar"; // Resetear clases base
        if (strength === 5) {
            bar.classList.add("bg-success");
        } else if (strength >= 3) {
            bar.classList.add("bg-warning");
        } else {
            bar.classList.add("bg-danger");
        }
    });

    // Cerrar y limpiar Modal de Cambio de Contraseña
    document.getElementById("btn_cerrar_modal_pass").addEventListener("click", () => {
        let passActualInput=document.getElementById("contraseña_actual");
        let passInput=document.getElementById("contraseña_cambio");
        let confirmInput=document.getElementById("confirm_cambio");
        let error=document.getElementById("error_pass");

        passActualInput.value="";
        error.textContent="";
        passInput.value="";
        confirmInput.value="";

        modalPass.hide();
    });
}

/**
 * Crea y muestra la tabla de juegos para una lista específica.
 *
 * @param {Array<Object>} juegos - Array de objetos con los datos de los juegos.
 * @param {string} tipo - Tipo de lista (wishlist, completed, playing, backlog).
 */
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
        enlace.href = `/TDG/juegos/view?juego=${juego.id}`;

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

/**
 * Genera la paginación para la lista de deseos (wishlist).
 *
 * @param {number} pagina - Página actual seleccionada.
 * @param {number} total_paginas - Número total de páginas disponibles.
 */
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

/**
 * Genera la paginación para la lista de juegos completados.
 * Crea enlaces numerados que permiten navegar entre páginas.
 *
 * @param {number} pagina - Página actual seleccionada.
 * @param {number} total_paginas - Número total de páginas disponibles.
 */
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

/**
 * Genera la paginación para la lista de juegos en curso (playing).
 * Crea enlaces numerados que permiten navegar entre páginas.
 *
 * @param {number} pagina - Página actual seleccionada.
 * @param {number} total_paginas - Número total de páginas disponibles.
 */
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

/**
 * Genera la paginación para la lista backlog.
 * Crea enlaces numerados que permiten navegar entre páginas.
 *
 * @param {number} pagina - Página actual seleccionada.
 * @param {number} total_paginas - Número total de páginas disponibles.
 */
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

/**
 * Realiza una petición AJAX para obtener y mostrar la lista de juegos
 * en la wishlist correspondiente a la página especificada.
 * También actualiza la paginación de la lista.
 *
 * @param {number|null} [nPagina=null] - Número de página a mostrar (1 por defecto).
 */
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

/**
 * Realiza una petición AJAX para obtener y mostrar la lista de juegos completados
 * correspondiente a la página especificada. Actualiza la tabla y la paginación.
 *
 * @param {number|null} [nPagina=null] - Número de página a mostrar (1 por defecto).
 */
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

/**
 * Realiza una petición AJAX para obtener y mostrar la lista de juegos en curso (playing)
 * correspondiente a la página especificada. Actualiza la tabla y la paginación.
 *
 * @param {number|null} [nPagina=null] - Número de página a mostrar (1 por defecto).
 */
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

/**
 * Realiza una petición AJAX para obtener y mostrar la lista backlog
 * correspondiente a la página especificada. Actualiza la tabla y la paginación.
 *
 * @param {number|null} [nPagina=null] - Número de página a mostrar (1 por defecto).
 */
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

eventos();