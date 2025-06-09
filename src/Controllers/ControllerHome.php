<?php

namespace App\Controllers;

use App\Core\Validators;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Review;
use App\Models\Venta;
use Dotenv\Validator;

class ControllerHome{
    /**
     * Muestra la vista de inicio de sesión si no hay una sesión activa.
     *
     * Si el usuario ya tiene una sesión activa, se le redirige a la página principal.
     * En caso contrario, se carga la vista del formulario de login.
     *
     * @return void
     */
    public function login(){

        if(!empty($_SESSION)){
            header("Location: /");
            exit;
        }
        
        include_once __DIR__. '/../Views/login.php';
    }
    /**
     * Muestra la vista de registro si no hay una sesión activa.
     *
     * Si el usuario ya tiene una sesión activa, se le redirige a la página principal.
     * En caso contrario, se carga la vista del formulario de registro.
     *
     * @return void
     */
    public function register(){
        
        if(!empty($_SESSION)){
            header("Location: /");
            exit;
        }

        include_once __DIR__. '/../Views/register.php';
    }
    /**
     * Muestra la página principal del sitio, con información dinámica.
     *
     * Este método obtiene los últimos juegos añadidos, los productos recientemente vendidos
     * y las últimas reviews. Asocia a cada review el nombre del juego correspondiente
     * para mostrarlo en la vista principal.
     *
     * Finalmente, incluye la vista `main.php` con los datos preparados.
     *
     * @return void
     */
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