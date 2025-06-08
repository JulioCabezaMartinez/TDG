const expresiones_regulares=new Map();

expresiones_regulares.set("correo", /^[\w.-]+@[\w.-]+\.\w+$/);
expresiones_regulares.set("password_completa", /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/);
expresiones_regulares.set("password_mayusculas", /[A-Z]/);
expresiones_regulares.set("password_minusculas", /[a-z]/);
expresiones_regulares.set("password_numero", /\d/);
expresiones_regulares.set("password_especial", /[!@#$%^&*(),.?":{}|<>]/);

/**
 * Actualiza el contenido y las clases CSS de un elemento para mostrar un estado de validación.
 *
 * @param {string} id - El ID del elemento HTML a actualizar.
 * @param {boolean} esValido - Indica si el estado es válido (true) o inválido (false).
 * @param {string} mensaje - Mensaje a mostrar junto al ícono de estado.
 *
 */
function actualizarEstado(id, esValido, mensaje) {
    const elemento = document.getElementById(id);
    elemento.classList.remove("text-success", "text-danger");
    if(esValido){
        elemento.innerHTML = `<i class="fa-regular fa-square-check"></i> ${mensaje}`;
        elemento.classList.add("text-success", "check");
    }else{
        elemento.innerHTML = `<i class="fa-solid fa-square-xmark"></i> ${mensaje}`;
        elemento.classList.add("text-danger", "error");
    }
}

/**
 * Función que asigna los eventos principales para la validación y envío del formulario de registro.
 * 
 * Eventos principales:
 * - Input en campo contraseña: valida la fortaleza de la contraseña y actualiza la barra de progreso y mensajes.
 * - Submit del formulario: valida los campos, muestra errores, y envía datos mediante fetch si todo es correcto.
 */
function eventos(){
    document.getElementById("password").addEventListener("input", function () {
        const password = this.value;
        let strength = 0;

        // Validaciones
        if (password.length >= 8) {
            actualizarEstado("length", true, "Minimum 8 characters");
            strength++;
        } else {
            actualizarEstado("length", false, "Minimum 8 characters");
        }

        if (expresiones_regulares.get("password_mayusculas").test(password)) {
            actualizarEstado("uppercase", true, "At least one capital letter");
            strength++;
        } else {
            actualizarEstado("uppercase", false, "At least one capital letter");
        }

        if (expresiones_regulares.get("password_minusculas").test(password)) {
            actualizarEstado("lowercase", true, "At least one lowercase letter");
            strength++;
        } else {
            actualizarEstado("lowercase", false, "At least one lowercase letter");
        }

        if (expresiones_regulares.get("password_numero").test(password)) {
            actualizarEstado("number", true, "At least one number");
            strength++;
        } else {
            actualizarEstado("number", false, "At least one number");
        }

        if (expresiones_regulares.get("password_especial").test(password)) {
            actualizarEstado("special", true, "At least one special character");
            strength++;
        } else {
            actualizarEstado("special", false, "At least one special character");
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

    //Validacion de los campos del fomulario
    document.getElementById("register-form").addEventListener("submit", function(event) {
    event.preventDefault();

    let errores={};

    // Obtener el valor de la contraseña
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm").value;
    const email = document.getElementById("email").value;
    const imagen = document.getElementById('imagen_perfil');
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellido").value;
    const nick= document.getElementById("nick").value;
    const direccion= document.getElementById("direccion").value;

    const errorGlobal=document.getElementById("error_global");
    const errorCorreo=document.getElementById("error_correo");
    const errorPassword=document.getElementById("error_password");
    const errorConfirm=document.getElementById("error_confirm");


    if(!password || !confirmPassword || !email || !nombre || !apellidos || !nick || !direccion){
        errores["campos"]="*All fields are required.";
    }

    // Expresión regular para validar la contraseña
    const pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/;

    

    // Verificar si la contraseña cumple con el patrón
    if (!pattern.test(password)) {
        
        errores["password"]="*Invalid password. Must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.";
        
    }

    if(!expresiones_regulares.get("correo").test(email)){

        errores["correo"]="*Invalid email.";
       
    }

    if(password !== confirmPassword){

        errores["confirm"]="*Passwords do not match.";

    }

    if(Object.keys(errores).length<=0){

        let formData = new FormData();
        formData.append("password", password);
        formData.append("email", email);
        formData.append("nombre", nombre);
        formData.append("apellido", apellidos);
        formData.append("nick", nick);
        formData.append("direccion", direccion);

        if (imagen.files.length > 0) {
            formData.append('imagen_perfil', imagen.files[0]);
        }

        fetch("/AJAX/registrar-usuario", {
            method: "POST",
            body: formData,

        }).then(response => response.json())
        .then(data => {

            if (data.result && data.result == "ok") {
                Swal.fire({
                    icon: "success",
                    title: "Registration completed",
                    text: "Registration successful. Welcome, " + nick + "!",
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
                

            }else if(data.result=="error" && data.Error=="correo"){
               
                errores["igual"]="The email is already registered.";
                errorCorreo.textContent="*The email is already registered.";
            
            }else{
               
                Swal.fire({
                    icon: "error",
                    title: "Something went wrong",
                    text: "Registration failed, please try again later.",
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
            }
                
        }).catch(error => {
            Swal.fire({
                    icon: "error",
                    title: "Something went wrong",
                    text: "Registration failed, please try again later.",
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
        });
    }else{
        for(let [error, mensaje] of Object.entries(errores)){
            if(error=="campos"){

                errorGlobal.textContent=mensaje;

            }else if(error=="password"){

                errorPassword.textContent=mensaje;

            }else if(error=="correo"){

                errorCorreo.textContent=mensaje;

            }else if(error=="confirm"){

                errorConfirm.textContent=mensaje;

            }else if(error=="igual"){
                errorCorreo.textContent=mensaje;
            }
        } 
    }

});

}

eventos();