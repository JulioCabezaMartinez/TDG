<?php

namespace App\Models;

use App\Core\EmptyModel;

use PDO;

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
     * Registra la compra de un juego por parte de un usuario.
     *
     * @param int $id_Juego ID del juego a comprar.
     * @param int $id_Usuario ID del usuario que realiza la compra.
     * @return void
     */
    public function comprarJuego($id_Juego, $id_Usuario): void {

    }

    // Crear Modelo aparte para las ventas.
    public function muestraColumnasVentas(){
        return parent::query("SHOW COLUMNS from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function muestraAllVentas(){
        return parent::query("Select * from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>