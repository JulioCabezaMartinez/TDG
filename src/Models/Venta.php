<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Interfaces\BusquedaAdmin;

use PDO;
use PDOException;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de ventas.
 */
class Venta extends EmptyModel implements BusquedaAdmin {
    /**
     * Constructor de la clase Venta.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('post_venta', 'id');
    }

    public function muestraColumnasVentas(){
        try {
            return parent::query("SHOW COLUMNS from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraColumnasVentas: " . $e->getMessage());
            return [];
        }
    }

    public function muestraAllVentas(){
        try {
            return parent::query("SELECT * from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraAllVentas: " . $e->getMessage());
            return [];
        }
    }

    public function muestraAllVentasLimit($inicio, $limit){
        try {
            $sql = "SELECT * FROM post_vendidos LIMIT {$inicio}, {$limit};";
            return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraAllVentasLimit: " . $e->getMessage());
            return [];
        }
    }

    public function cuentaVentas(){
        try {
            return (int) $this->query("SELECT COUNT(*) FROM post_vendidos;")->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en cuentaVentas: " . $e->getMessage());
            return 0;
        }
    }

    public function getCompra($id_producto, $id_usuario, $fecha){
        $sql="SELECT * FROM post_vendidos WHERE id_Post=:id_producto AND id_Comprador=:id_usuario AND Fecha=:fecha";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam("id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam("id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function crearCompra($data){
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO post_vendidos ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    public function deleteCompra($id_producto, $id_usuario, $fecha){
        $sql= "DELETE FROM post_vendidos WHERE id_Post=:id_producto AND id_Comprador=:id_usuario AND Fecha=:fecha";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam("id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam("id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateCompra($data){
        $sql = "UPDATE post_vendidos SET id_Post={$data["id_Post"]}, id_Comprador={$data["id_Comprador"]}, Fecha='{$data["Fecha"]}' WHERE id_Post={$data["id_PostAntiguo"]} AND id_Comprador={$data["id_CompradorAntiguo"]} AND Fecha='{$data["FechaAntigua"]}'";
        return $this->query($sql);
    }

    public function getListSells(int $inicio, int $limit, array $filtros = []){
        try {
            $sql = "SELECT v.* FROM {$this->table} v JOIN usuarios u ON v.id_Vendedor = u.id";

            $conditions = [];

            if (!empty($filtros)) {
                if (!empty($filtros['nombre'])) {
                    $conditions[] = "v.id_juego IN (SELECT id FROM juegos WHERE nombre LIKE '{$filtros['nombre']}')";
                }
                if (!empty($filtros['Stock'])) {
                    if ($filtros['Stock'] == "si") {
                        $conditions[] = "v.Stock >= 1";
                    } else {
                        $conditions[] = "v.Stock <= 0";
                    }
                }
                if (!empty($filtros['precioMin'])) {
                    $conditions[] = "v.Precio >= {$filtros['precioMin']}";
                }
                if (!empty($filtros['precioMax'])) {
                    $conditions[] = "v.Precio <= {$filtros['precioMax']}";
                }
                if (!empty($filtros['Consola'])) {
                    $conditions[] = "v.Consola = {$filtros['Consola']}";
                }
                if (!empty($filtros['Estado'])) {
                    $conditions[] = "v.Estado = '{$filtros['Estado']}'";
                }
                $conditions[]="v.Estado_venta != 'Sin Stock'";
            }

            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
             else {
                $sql .= " WHERE v.Estado_venta != 'Sin Stock'";
            }

            $sql .= " ORDER BY u.Premium DESC";
            $sql .= " LIMIT {$inicio}, {$limit}";

            return parent::query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getListSells: " . $e->getMessage());
            return [];
        }
    }

    public function getCountListaVentas(): int{
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE Estado_venta != 'Sin Stock'";
            return (int) parent::query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getCount: " . $e->getMessage());
            return 0;
        }
    }

    public function getCountFiltros($filtros){
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table}";

            if (!empty($filtros)) {
                $conditions = [];

                if (!empty($filtros['nombre'])) {
                    $conditions[] = "id_juego IN (SELECT id from juegos where nombre LIKE '{$filtros['nombre']}')";
                }
                if (!empty($filtros['Stock'])) {
                    if ($filtros['Stock'] == "si") {
                        $conditions[] = "Stock > 1";
                    } else {
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

                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                } else {
                    $sql .= " WHERE Estado_venta != 'Sin Stock'";
                }
            } else {
                $sql .= " WHERE Estado_venta != 'Sin Stock'";
            }

            return (int) parent::query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getCountFiltros: " . $e->getMessage());
            return 0;
        }
    }

    public function bajarStock($id_producto){
        try {
            $producto = $this->getById($id_producto);

            if ($producto["id"] == -1) {
                return true;
            }

            if ($producto["Stock"] <= 1) {
                return $this->update(["Stock" => 0, "Estado_venta" => "Sin Stock"], $id_producto);
            } else {
                return $this->update(["Stock" => $producto["Stock"] - 1], $id_producto);
            }
        } catch (PDOException $e) {
            error_log("Error en bajarStock: " . $e->getMessage());
            return false;
        }
    }

    public function vaciarProducto($id_producto){
        try {
            return $this->update(["Stock" => 0, "Estado_venta" => "Sin Stock"], $id_producto);
        } catch (PDOException $e) {
            error_log("Error en vaciarProducto: " . $e->getMessage());
            return false;
        }
    }

    public function agregarVendido($id_producto, $id_usuario, $fecha_compra){
        try {
            return $this->query("INSERT INTO post_vendidos (id_Post, id_Comprador, Fecha) VALUES ({$id_producto}, {$id_usuario}, '{$fecha_compra}');");
        } catch (PDOException $e) {
            error_log("Error en agregarVendido: " . $e->getMessage());
            return false;
        }
    }

    public function getProductosUsuario($id_usuario) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getProductosUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function getCountProductosUsuario($id_usuario) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id_Vendedor = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getCountProductosUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function getListProductosUsuario($id_usuario, $inicio, $limit) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_Vendedor = :id_usuario LIMIT :inicio, :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getListProductosUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function getComprasUsuario($id_usuario) {
        try {
            $sql = "SELECT * FROM post_vendidos WHERE id_Comprador = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getComprasUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function getCountComprasUsuario($id_usuario) {
        try {
            $sql = "SELECT COUNT(*) FROM post_vendidos WHERE id_Comprador = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getCountComprasUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function getListComprasUsuario($id_usuario, $inicio, $limit) {
        try {
            $sql = "SELECT * FROM post_vendidos WHERE id_Comprador = :id_usuario LIMIT :inicio, :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getListComprasUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function buscarAdmin($textoBusqueda, $inicio, $limit){

        try {
            $sql = "SELECT * FROM {$this->table} WHERE Titulo LIKE :textoBusqueda LIMIT {$inicio}, {$limit}";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en buscarAdmin: " . $e->getMessage());
            return [];
        }

    }

    public function buscarAdminCount($textoBusqueda): int{

        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE Titulo LIKE :textoBusqueda";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en buscarAdminCount: " . $e->getMessage());
            return 0;
        }
    }

    public function addImagen($imagen){
        $Nombreimagen = uniqid();

        $extension = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];

        if ($imagen['size'] > (12 * 1024 * 1204)) { //Que el tamaño no sea mayor de 12 mb

            echo json_encode(["error" => "Error: Imagen demasiado pesada."]);
            exit;
        } elseif (!in_array($extension, $extensionesPermitidas)) {

            echo json_encode(["error" => "Error: El archivo tiene un tipo no permitido."]);
            exit;
        } else {

            $filename = $Nombreimagen . ".jpg";
            $tempName = $imagen['tmp_name'];
            if (isset($filename)) {
                if (!empty('$filename')) {
                    $location = __DIR__ . "/../../public/IMG/Productos-img/" . $filename;
                    move_uploaded_file($tempName, $location);
                }
            }
        }

        return $Nombreimagen;
    }

    public function eliminarImagen($rutaImagen){
        try {
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Error en eliminarImagen: " . $e->getMessage());
            return false;
        }
    }
}
?>