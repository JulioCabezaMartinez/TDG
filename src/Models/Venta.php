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

    public function muestraColumnasVentas(){
        return parent::query("SHOW COLUMNS from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function muestraAllVentas(){
        return parent::query("Select * from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getListSells(int $inicio, int $limit, array $filtros=[]){

        $sql = "SELECT * FROM {$this->table}";

        if (!empty($filtros)) {
            $conditions = []; 

            //Si hay filtro para 'Nombre', agregamos la condición correspondiente
            if (!empty($filtros['nombre'])) {
                $conditions[] = "id_juego IN (SELECT id from juegos where nombre LIKE '{$filtros['nombre']}')";
            }
           
            if (!empty($filtros['Stock'])) {
                if($filtros['Stock']=="Si"){
                    $conditions[] = "Stock > 1";
                }else{
                    $conditions[] = "Stock <= 0";
                }
            }

            if (!empty($filtros['precioMin'])) {
                $conditions[] = "Precio >= {$filtros['precioMin']}";
            }

            if (!empty($filtros['precioMax'])) {
                $conditions[] = "Precio <= {$filtros['precioMax']}";
            }

            if (!empty($filtros['Consola'])) {
                $conditions[] = "Consola = {$filtros['Consola']}";
            }

            if (!empty($filtros['Estado'])) {
                $conditions[] = "Estado = '{$filtros['Estado']}'";
            }

            // Si hay condiciones, las unimos con AND y las añadimos a la consulta
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
        }
        // Añadimos el LIMIT (esto siempre se añade al final)
        $sql .= " LIMIT {$inicio}, {$limit}";

        // return $sql;

        return parent::query($sql)->fetchAll(\PDO::FETCH_ASSOC); //Se puede poner $param pero no en el Limit, execute(sql, param) no admite parametros como Integers.
    }

    public function getCountFiltros($filtros){
         $sql = "SELECT COUNT(*) FROM {$this->table}";

        if (!empty($filtros)) {
            $conditions = []; 

            //Si hay filtro para 'Nombre', agregamos la condición correspondiente
            if (!empty($filtros['nombre'])) {
                $conditions[] = "id_juego IN (SELECT id from juegos where nombre LIKE '{$filtros['nombre']}')";
            }
           
            if (!empty($filtros['Stock'])) {
                if($filtros['Stock']=="Si"){
                    $conditions[] = "Stock > 1";
                }else{
                    $conditions[] = "Stock <= 0";
                }
            }

            if (!empty($filtros['precioMin'])) {
                $conditions[] = "Precio > {$filtros['precioMin']}";
            }

            if (!empty($filtros['precioMax'])) {
                $conditions[] = "Precio < {$filtros['precioMax']}";
            }

            if (!empty($filtros['Consola'])) {
                $conditions[] = "Consola = {$filtros['Consola']}";
            }

            if (!empty($filtros['Estado'])) {
                $conditions[] = "Estado = '{$filtros['Estado']}'";
            }

            // Si hay condiciones, las unimos con AND y las añadimos a la consulta
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
        }

        return parent::query($sql)->fetchColumn();
    }

    public function bajarStock($id_producto){
        $producto=$this->getById($id_producto);

        if($producto["Stock"]<=1){
            return $this->update(["Stock"=>0, "Estado_venta"=>"Sin Stock"], $id_producto);
        }else{
            return $this->update(["Stock"=>$producto["Stock"]-1], $id_producto);
        }
    }

    public function agregarVendido($id_producto, $id_usuario, $fecha_compra){
        return $this->query("INSERT INTO post_vendidos (id_Post, id_Comprador, Fecha) VALUES ({$id_producto}, {$id_usuario}, '{$fecha_compra}');");
    }

    public function getProductosUsuario($id_usuario) {
        $sql = "SELECT * FROM {$this->table} WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountProductosUsuario($id_usuario) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id_Vendedor = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getListProductosUsuario($id_usuario, $inicio, $limit) {
        $sql = "SELECT * FROM {$this->table} WHERE id_Vendedor = :id_usuario LIMIT :inicio, :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComprasUsuario($id_usuario) {
        $sql = "SELECT * FROM post_vendidos WHERE id_Comprador = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountComprasUsuario($id_usuario) {
        $sql = "SELECT COUNT(*) FROM post_vendidos WHERE id_Comprador = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getListComprasUsuario($id_usuario, $inicio, $limit) {
        $sql = "SELECT * FROM post_vendidos WHERE id_Comprador = :id_usuario LIMIT :inicio, :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>