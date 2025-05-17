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

    

    // Crear Modelo aparte para las ventas.
    public function muestraColumnasVentas(){
        return parent::query("SHOW COLUMNS from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function muestraAllVentas(){
        return parent::query("Select * from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNew(): array {
        return parent::query("SELECT * FROM {$this->table} ORDER BY id  DESC LIMIT 10")->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>