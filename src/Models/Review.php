<?php

namespace App\Models;

use App\Core\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de reviews.
 */
class Review extends EmptyModel {
    /**
     * Constructor de la clase Review.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('reviews', 'id');
    }
}
?>