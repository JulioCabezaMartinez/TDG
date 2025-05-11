<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Review;

class ControllerHome{
    
    public function login(){
        include_once __DIR__. '/../Views/login.php';
    }

    public function register(){
        include_once __DIR__. '/../Views/register.php';
    }

    public function registrarUsuario(){
        $usuario = new Usuario();
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $pass = $_POST['password'];
        $nick = $_POST['username'];
        $imagen = $_FILES['imagen_perfil']['name'] ?? null; // Obtener la imagen si se proporciona

        // Lógica para registrar al usuario
        $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $imagen);

        if ($resultado === "Exito") {
            // Registro exitoso
            echo "Registro exitoso. Bienvenido, $nick!";
        } else {
            // Manejar el error de registro
            echo "Error: " . $resultado;
        }
    }

    public function index(){

        $juegoDB = new Juego();
        $review = new Review();
        $reviewBD=new Review();

        $mas_vendidos = $juegoDB->getNew(); // Obtener los juegos más vendidos (recien añadidos).

        $lista_reviews=$reviewBD->ultimasReviews();

        foreach($lista_reviews as &$review){
            $juego = $juegoDB->getbyId($review["id_Juego"]);

            $review["juego"]=$juego["Nombre"];
        }
        
        include_once __DIR__. '/../Views/main.php'; // Cargar la vista principal.
    }

}