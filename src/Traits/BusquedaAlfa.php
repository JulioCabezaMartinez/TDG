<?php

namespace App\Traits;

use PDO;

trait BusquedaAlfa{
    /**
     * Obtiene todos los registros de la tabla ordenados alfabéticamente por el campo 'Nombre'.
     *
     * @return array Lista de registros ordenados por Nombre, o arreglo vacío en caso de error.
     */
    public function getAllOrderByAlfabet() {
        $sql = "SELECT * FROM {$this->table} ORDER BY Nombre ASC;";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}