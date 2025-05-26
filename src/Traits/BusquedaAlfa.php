<?php

namespace App\Traits;

use PDO;

trait BusquedaAlfa{
    
    public function getAllOrderByAlfabet() {
        $sql = "SELECT * FROM {$this->table} ORDER BY Nombre ASC;";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}