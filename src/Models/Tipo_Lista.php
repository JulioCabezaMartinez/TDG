<?php

namespace App\Models;

use App\Models\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de tipos de lista.
 */
class Tipo_Lista extends EmptyModel {
    /**
     * Constructor de la clase Tipo_Lista.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('tipos_lista');
    }
}
?>