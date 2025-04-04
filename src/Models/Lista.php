<?php

namespace App\Models;

use App\Models\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de listas.
 */
class Lista extends EmptyModel {
    /**
     * Constructor de la clase Lista.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('listas', 'id');
    }

    /**
     * Agrega un juego a una lista específica.
     *
     * @param int $id_Juego ID del juego a agregar.
     * @param int $id_Lista ID de la lista donde se agregará el juego.
     * @return void
     */
    public function addJuegotoLista($id_Juego, $id_Lista): void {}

    /**
     * Elimina un juego de una lista específica.
     *
     * @param int $id_Juego ID del juego a eliminar.
     * @param int $id_Lista ID de la lista de la que se eliminará el juego.
     * @return void
     */
    public function removeJuegotoLista($id_Juego, $id_Lista): void {}
}
?>