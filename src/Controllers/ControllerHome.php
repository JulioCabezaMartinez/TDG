<?php

namespace App\Controllers;

use App\Core\Validators;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Review;
use Dotenv\Validator;

class ControllerHome{
    
    public function login(){
        include_once __DIR__. '/../Views/login.php';
    }

    public function register(){
        include_once __DIR__. '/../Views/register.php';
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

    public function admin(){
        include_once __DIR__. '/../Views/panelAdmin.php';
    }

}