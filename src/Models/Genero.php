<?php

namespace App\Models;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de géneros.
 */
class Genero extends EmptyModel {
    /**
     * Constructor de la clase Genero.
     * Configura la tabla y la clave primaria asociadas al modelo.
     */
    public function __construct() {
        parent::__construct('generos', 'id');
    }
}
?>