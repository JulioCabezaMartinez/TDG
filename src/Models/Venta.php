<?php

namespace App\Models;

use App\Core\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de ventas.
 */
class Venta extends EmptyModel {
    /**
     * Constructor de la clase Venta.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('post_venta', 'id');
    }

    /**
     * Procesa el pago de un juego mediante Paypal.
     *
     * @return void
     */
    public function pagarJuego() {
        //Paypal
    }

    /**
     * Registra la compra de un juego por parte de un usuario.
     *
     * @param int $id_Juego ID del juego a comprar.
     * @param int $id_Usuario ID del usuario que realiza la compra.
     * @return void
     */
    public function comprarJuego($id_Juego, $id_Usuario): void {}
}
?>