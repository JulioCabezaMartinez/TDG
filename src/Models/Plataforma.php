<?php

namespace App\Models;

use App\Models\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de plataformas.
 */
class Plataforma extends EmptyModel {
    /**
     * Constructor de la clase Plataforma.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('plataformas', 'id');
    }
}
?>