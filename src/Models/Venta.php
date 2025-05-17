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

    public function getListSells(int $inicio, int $limit){

        $sql = "SELECT * FROM {$this->table}";

        // if (!empty($filtros)) {
        //     $conditions = []; 

        //     // Si hay filtro para 'Nombre', agregamos la condición correspondiente
        //     if (!empty($filtros['nombre'])) {
        //         $conditions[] = "Nombre LIKE '{$filtros['nombre']}'";
        //     }

        //     // Si hay filtro para 'fechaSalida', agregamos la condición correspondiente
        //     if (!empty($filtros['fechaSalida'])) {
        //         $conditions[] = "Anyo_salida > '{$filtros['fechaSalida']}' AND Anyo_salida < '{$filtros['fechaNextMonth']}'";
        //     }

        //     // Si hay filtro para 'Calificacion', agregamos la condición correspondiente
        //     if (!empty($filtros['calificacion'])) {
        //         $conditions[] = "calificacion > {$filtros['calificacion']} AND calificacion < {$filtros['calificacion']}+1";
        //     }

        //     // Si hay condiciones, las unimos con AND y las añadimos a la consulta
        //     if (!empty($conditions)) {
        //         $sql .= " WHERE " . implode(" AND ", $conditions);
        //     }
        // }
        // Añadimos el LIMIT (esto siempre se añade al final)
        $sql .= " LIMIT {$inicio}, {$limit}";

        return parent::query($sql)->fetchAll(\PDO::FETCH_ASSOC); //Se puede poner $param pero no en el Limit, execute(sql, param) no admite parametros como Integers.
    }
}
?>