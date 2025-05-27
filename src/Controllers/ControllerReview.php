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

        $id=Validators::evitarInyeccion($_GET["id"]);
        $juego=$juegoBD->getById($id);

        include_once 'src/Views/lista_reviews.php';
    }
}