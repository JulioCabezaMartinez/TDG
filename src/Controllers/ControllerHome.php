<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Review;

class ControllerHome{
    

    public static function index(){

        $juego = new Juego();
        $review = new Review();

        $mas_vendidos = $juego->getNew(); // Obtener los juegos más vendidos (recien añadidos).
        $reviews_populares = []; // Obtener las reviews populares (recien añadidas).
        
        require_once __DIR__. '/../Views/main.php'; // Cargar la vista principal.
    }

}