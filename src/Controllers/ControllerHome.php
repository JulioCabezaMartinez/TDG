<?php

namespace App\Controllers;

use App\Core\Validators;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Review;
use App\Models\Venta;
use Dotenv\Validator;

class ControllerHome{
    
    public function login(){

        if(!empty($_SESSION)){
            header("Location: /");
            exit;
        }
        
        include_once __DIR__. '/../Views/login.php';
    }

    public function register(){
        
        if(!empty($_SESSION)){
            header("Location: /");
            exit;
        }

        include_once __DIR__. '/../Views/register.php';
    }
    public function index(){

        $juegoDB = new Juego();
        $reviewBD=new Review();
        $ventaDB=new Venta();

        $ultimos_juegos=$juegoDB->getNew();

        $mas_vendidos = $ventaDB->getNew(); // Obtener las ventas recien añadidos.

        $lista_reviews=$reviewBD->ultimasReviews();

        foreach($lista_reviews as &$review){
            $juego = $juegoDB->getbyId($review["id_Juego"]);

            $review["juego"]=$juego["Nombre"];
        }
        
        include_once __DIR__. '/../Views/main.php'; // Cargar la vista principal.
    }
}