const expresiones_regulares=new Map();

expresiones_regulares.set("correo", /^[\w.-]+@[\w.-]+\.\w+$/);
expresiones_regulares.set("password_completa", /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/);
expresiones_regulares.set("password_mayusculas", /[A-Z]/);
expresiones_regulares.set("password_minusculas", /[a-z]/);
expresiones_regulares.set("password_numero", /\d/);
expresiones_regulares.set("password_especial", /[!@#$%^&*(),.?":{}|<>]/);

// Función auxiliar para actualizar los textos y clases de validación
function actualizarEstado(id, esValido, mensaje) {
    const elemento = document.getElementById(id);
    elemento.textContent = mensaje;
    elemento.classList.remove("text-success", "text-danger");
    elemento.classList.add(esValido ? "text-success" : "text-danger");
}

function eventos(){
    document.getElementById("password").addEventListener("input", function () {
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

    //Validacion de los campos del fomulario
    document.getElementById("register-form").addEventListener("submit", function(event) {
    event.preventDefault();

    let errores=[];

    // Obtener el valor de la contraseña
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    const email = document.getElementById("email").value;
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellido").value;
    const correo = document.getElementById("correo").value;
    const nick= document.getElementById("nick").value;
    const direccion= document.getElementById("direccion").value;

    if(!password || !confirmPassword || !email || !nombre || !apellidos || !correo || !nick || !direccion){
        errores["campos"]="Todos los campos son obligatorios.";
    }

    // Expresión regular para validar la contraseña
    const pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/;

    

    // Verificar si la contraseña cumple con el patrón
    if (!pattern.test(password)) {
        
        errores["password"]="Contraseña no válida. Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";
        
    }else if(expresiones_regulares.get("correo").test(email)){
        errores["correo"]="Correo no válido.";
       
    }else if(password !== confirmPassword){

        errores["confirm"]="Las contraseñas no coinciden.";

    }else{

        fetch("/TDG/registrar-usuario", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                password: password,
                email: email,
                nombre: nombre,
                apellidos: apellidos,
                nick: nick,
                direccion: direccion
            })
        }).then(response => response.json())
        .then(data => {
            if (data.result && data.result == "ok") {
                Swal.fire({
                    icon: "success",
                    title: "Registro completado",
                    text: "Registro exitoso. Bienvenido, " + nick + "!",
                });
                

            }else if(data.result=="error" && data.error=="correo"){
               
                errores["correo"]="El correo ya está registrado.";
                
            
            }else{
                Swal.fire({
                    icon: "error",
                    title: "Algo salió mal",
                    text: "Ha fallado el registro, intentelo más tarde.",
                });
            }
                
        }).catch(error => {
            console.error("Error:", error);
        });
    }
});

}

eventos();