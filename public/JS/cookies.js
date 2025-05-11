"use strict";

// Función para obtener el valor de una cookie por su nombre
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
function setCookie(name, value, days) {
  let date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Expiración en días
  let expires = "expires=" + date.toUTCString();
  
  document.cookie = name + "=" + value + ";" + expires + ";path=/"; // Establece la cookie
}

// Función para cambiar el color de fondo y guardarlo en la cookie
function cambiarNeon(color) {
  document.documentElement.style.setProperty('--color-borde-neon', color);
  setCookie('colorNeon', color, 1); // Guardar la cookie por 1 día.
  console.log("Color Guardado");
}

function setNeon(){
    let color=getCookie("colorNeon");
    document.documentElement.style.setProperty('--color-borde-neon', color);
    console.log("Color Neon" + color);
}

function eventos(){
    let guardarNeon=document.getElementById("btn-neon");

    guardarNeon.addEventListener("click", function(){

      let color = document.getElementById("neon_cookie").value;
      console.log("Guardando neon");
      let cookie = getCookie("colorNeon");
      cambiarNeon(color);

    });
}

if(getCookie("colorNeon")){
    setNeon();
}

eventos();


