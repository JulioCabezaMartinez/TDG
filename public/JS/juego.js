/**
 * Obtiene el estado de las listas del juego (Wishlist, Backlog, Completed, Playing)
 * desde el servidor mediante una solicitud AJAX y genera dinámicamente los botones
 * correspondientes en el DOM.
 *
 * Utiliza el ID del juego almacenado en un campo oculto del HTML.
 * 
 * Las listas se representan con íconos de FontAwesome, y su estado (activo o no)
 * se determina a partir de los datos devueltos por el servidor.
 */
function compruebaLista(){
    let id_juego=document.getElementById("hidden_id_juego").value;

    let formData=new FormData();
    formData.append("id_juego", id_juego);

    fetch("/TDG/AJAX/botones_juego", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data =>{
        let arrayListas=data.estados;
        let divBotones=document.getElementById("btn_listas");

        // --- Wishlist ---
        const wishlistDiv = document.createElement('div');
        wishlistDiv.id = `wish@${id_juego}`;
        wishlistDiv.className = 'btn-lista boton btn_redondo wishlist';

        const wishlistTexto = document.createElement('p');
        wishlistTexto.textContent = 'Añadir a la lista de Deseados';

        const wishlistIcono = document.createElement('i');
        wishlistIcono.className = `${arrayListas[0] ? 'fa-solid' : 'fa-regular'} fa-heart btn_wishlist icono_ajustable`;

        wishlistDiv.appendChild(wishlistTexto);
        wishlistDiv.appendChild(wishlistIcono);

        // --- Listas adicionales: Backlog, Completed, Playing ---
        const innerDiv = document.createElement('div');

        const listas = [
            { tipo: 'back', clase: 'backlog', icono: 'fa-clock', estado: arrayListas[1] },
            { tipo: 'comp', clase: 'completed', icono: 'fa-circle-check', estado: arrayListas[2] },
            { tipo: 'play', clase: 'playing', icono: 'fa-circle-play', estado: arrayListas[3] }
        ];

        listas.forEach(lista => {
            const div = document.createElement('div');
            div.id = `${lista.tipo}@${id_juego}`;
            div.className = `btn-lista boton btn_redondo ${lista.clase}`;

            const icon = document.createElement('i');
            icon.className = `${lista.estado ? 'fa-solid' : 'fa-regular'} ${lista.icono} icono_ajustable`;

            div.appendChild(icon);
            innerDiv.appendChild(div);
        });

        // Ensamblar todo
        divBotones.appendChild(wishlistDiv);
        divBotones.appendChild(innerDiv);
    })
    .catch(error=>{
        console.log(error);
    })
}

compruebaLista();