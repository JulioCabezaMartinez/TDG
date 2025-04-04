<?php

namespace App\Controllers;

use App\Models\Review;

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

    /**
     * Obtiene todas las reviews.
     *
     * @return void
     */
    public function getAllReviews(): void {
        $this->review->getAll();
        require_once '../src/Views/reviews.php';
    }

    /**
     * Obtiene una review por su ID.
     *
     * @param int $id ID de la review a obtener.
     * @return void
     */
    public function getReviewById($id): void {
        $this->review->getById($id);
        require_once '../src/Views/reviews.php';
    }

    /**
     * Actualiza una review existente.
     *
     * @param int $id ID de la review a actualizar.
     * @param array $data Datos actualizados de la review.
     * @return void
     */
    public function updateReview($id, $data): void {
        $this->review->update(id: $id, data: $data);
        require_once '../src/Views/reviews.php';
    }

    /**
     * Elimina una review por su ID.
     *
     * @param int $id ID de la review a eliminar.
     * @return void
     */
    public function deleteReview($id): void {
        $this->review->delete($id);
        require_once '../src/Views/reviews.php';
    }
}