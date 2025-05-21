<?php

namespace App\Controllers;

use App\Models\Review;
use App\Models\Juego;
use App\Models\Usuario;

use App\Core\Validators;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Review.
 */
class ControllerReview {
    /**
     * @var Review Instancia del modelo Review.
     */
    private $review;

    /**
     * Constructor de la clase ControllerReview.
     * Inicializa una nueva instancia del modelo Review.
     */
    public function __construct() {
        $this->review = new Review();
    }

    public function lista_reviews_juego(){
        $juegoBD=new Juego();
        $reviewBD=new Review();
        $usuarioBD=new Usuario();
        $id=Validators::evitarInyeccion($_GET["id"]);
        $juego=$juegoBD->getById($id);

        $lista_reviews=$reviewBD->getAllReviewsJuego($juego["id"]); // Aqui va el $_GET["id"].

        include_once 'src/Views/lista_reviews.php';
    }
}