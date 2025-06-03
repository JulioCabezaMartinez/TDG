"use strict";

// Función para obtener el valor de una cookie por su nombre
/**
 * Obtiene el valor de una cookie dado su nombre.
 *
 * @param {string} name - El nombre de la cookie a buscar.
 * @returns {string|null} El valor de la cookie si existe, o null si no se encuentra.
 */
function getCookie(name) {
  let cookieArr = document.cookie.split(";");
  for (let i = 0; i < cookieArr.length; i++) {
    let cookie = cookieArr[i].trim();
    if (cookie.indexOf(name + "=") === 0) {
      return cookie.substring(name.length + 1, cookie.length);
    }
  }
  return null;
}

// Función para establecer una cookie con un valor y tiempo de expiración
/**
 * Establece una cookie con nombre, valor y tiempo de expiración en días.
 *
 * @param {string} name - El nombre de la cookie.
 * @param {string} value - El valor que se quiere guardar.
 * @param {number} days - El número de días que debe durar la cookie.
 */
function setCookie(name, value, days) {
  let date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Expiración en días
  let expires = "expires=" + date.toUTCString();
  
  document.cookie = name + "=" + value + ";" + expires + ";path=/"; // Establece la cookie
}

// Función para cambiar el color de fondo y guardarlo en la cookie
/**
 * Cambia el valor de la variable CSS '--color-borde-neon' y guarda el color en una cookie.
 *
 * @param {string} color - El nuevo color que se aplicará al borde neón.
 */
function cambiarNeon(color) {
  document.documentElement.style.setProperty('--color-borde-neon', color);
  setCookie('colorNeon', color, 1); // Guardar la cookie por 1 día.
  console.log("Color Guardado");
}

/**
 * Establece el color neón previamente guardado en la cookie (si existe).
 */
function setNeon(){
    let color=getCookie("colorNeon");
    document.documentElement.style.setProperty('--color-borde-neon', color);
    console.log("Color Neon" + color);
}

/**
 * Registra los eventos de los botones que permiten guardar el color neón personalizado.
 * Aplica el color ingresado tanto en dispositivos móviles como de escritorio.
 */
function eventos() {
  let guardarNeonMovil = document.getElementById("btn-neon-movil");
  let guardarNeonEscritorio = document.getElementById("btn-neon-escritorio");

  guardarNeonMovil.addEventListener("click", function () {

    let color = document.getElementById("neon-cookie-movil").value;
    console.log("Guardando neon");
    let cookie = getCookie("colorNeon");
    cambiarNeon(color);

  });

  guardarNeonEscritorio.addEventListener("click", function () {

    let color = document.getElementById("neon-cookie-escritorio").value;
    console.log("Guardando neon");
    let cookie = getCookie("colorNeon");
    cambiarNeon(color);

  });
}

if(getCookie("colorNeon")){
    setNeon();
}

eventos();


