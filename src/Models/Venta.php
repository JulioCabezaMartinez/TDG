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

    /**
     * Obtiene la estructura (columnas) de la tabla post_vendidos.
     *
     * @return array Lista asociativa con las columnas de la tabla.
     */
    public function muestraColumnasVentas(){
        try {
            return parent::query("SHOW COLUMNS from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraColumnasVentas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene todos los registros de la tabla post_vendidos.
     *
     * @return array Lista asociativa con todas las ventas.
     */
    public function muestraAllVentas(){
        try {
            return parent::query("SELECT * from post_vendidos;")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraAllVentas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un rango limitado de registros de la tabla post_vendidos.
     *
     * @param int $inicio Índice desde donde empezar a obtener registros.
     * @param int $limit Cantidad máxima de registros a obtener.
     * @return array Lista asociativa con las ventas limitadas.
     */
    public function muestraAllVentasLimit($inicio, $limit){
        try {
            $sql = "SELECT * FROM post_vendidos LIMIT {$inicio}, {$limit};";
            return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en muestraAllVentasLimit: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cuenta el total de registros en la tabla post_vendidos.
     *
     * @return int Cantidad total de ventas.
     */
    public function cuentaVentas(){
        try {
            return (int) $this->query("SELECT COUNT(*) FROM post_vendidos;")->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en cuentaVentas: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene una compra específica filtrando por producto, usuario y fecha.
     *
     * @param int $id_producto ID del producto comprado.
     * @param int $id_usuario ID del comprador.
     * @param string $fecha Fecha de la compra (formato fecha en DB).
     * @return array|false Datos de la compra o false si no existe.
     */
    public function getCompra($id_producto, $id_usuario, $fecha){
        $sql="SELECT * FROM post_vendidos WHERE id_Post=:id_producto AND id_Comprador=:id_usuario AND Fecha=:fecha";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam("id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam("id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Inserta una nueva compra en la tabla post_vendidos.
     *
     * @param array $data Array asociativo con los campos y valores a insertar.
     * @return string ID del último registro insertado.
     */
    public function crearCompra($data){
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO post_vendidos ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * Elimina una compra específica de la tabla post_vendidos.
     *
     * @param int $id_producto ID del producto.
     * @param int $id_usuario ID del comprador.
     * @param string $fecha Fecha de la compra.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function deleteCompra($id_producto, $id_usuario, $fecha){
        $sql= "DELETE FROM post_vendidos WHERE id_Post=:id_producto AND id_Comprador=:id_usuario AND Fecha=:fecha";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam("id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam("id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam("fecha", $fecha, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Actualiza una compra en la tabla post_vendidos.
     *
     * @param array $data Array asociativo con los datos a actualizar y las claves antiguas:
     *  - id_Post: nuevo ID del producto
     *  - id_Comprador: nuevo ID comprador
     *  - Fecha: nueva fecha
     *  - id_PostAntiguo: ID anterior del producto
     *  - id_CompradorAntiguo: ID anterior del comprador
     *  - FechaAntigua: fecha anterior
     * @return mixed Resultado de la consulta (generalmente PDOStatement o false).
     */
    public function updateCompra($data){
        $sql = "UPDATE post_vendidos SET id_Post={$data["id_Post"]}, id_Comprador={$data["id_Comprador"]}, Fecha='{$data["Fecha"]}' WHERE id_Post={$data["id_PostAntiguo"]} AND id_Comprador={$data["id_CompradorAntiguo"]} AND Fecha='{$data["FechaAntigua"]}'";
        return $this->query($sql);
    }

    /**
     * Obtiene una lista paginada de ventas aplicando filtros opcionales.
     *
     * @param int $inicio Índice desde donde iniciar la consulta.
     * @param int $limit Cantidad máxima de resultados a devolver.
     * @param array $filtros Opcionales. Filtros para refinar la búsqueda:
     *  - 'nombre' (string): nombre del juego (LIKE).
     *  - 'Stock' (string): "si" para stock >= 1, otro valor para stock <= 0.
     *  - 'precioMin' (float|int): precio mínimo.
     *  - 'precioMax' (float|int): precio máximo.
     *  - 'Consola' (int): ID de consola.
     *  - 'Estado' (string): estado del producto.
     * @return array Lista de ventas que cumplen con los filtros y paginación.
     */
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

    /**
     * Obtiene el conteo total de ventas que no están en estado "Sin Stock".
     *
     * @return int Número total de ventas activas.
     */
    public function getCountListaVentas(): int{
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE Estado_venta != 'Sin Stock'";
            return (int) parent::query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en getCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el conteo de ventas que cumplen con los filtros proporcionados.
     *
     * @param array $filtros Array de filtros iguales a los de getListSells.
     * @return int Número de ventas que cumplen con los filtros.
     */
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

    /**
     * Disminuye en 1 la cantidad de stock del producto dado. 
     * Si el stock es 1 o menos, actualiza el stock a 0 y cambia el estado de venta a "Sin Stock".
     *
     * @param int $id_producto ID del producto cuyo stock se desea bajar.
     * @return bool True si la operación fue exitosa, false en caso de error.
     */
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

    /**
     * Vacía el stock de un producto y cambia su estado a "Sin Stock".
     *
     * @param int $id_producto ID del producto a actualizar.
     * @return bool True si la operación fue exitosa, false si ocurrió un error.
     */
    public function vaciarProducto($id_producto){
        try {
            return $this->update(["Stock" => 0, "Estado_venta" => "Sin Stock"], $id_producto);
        } catch (PDOException $e) {
            error_log("Error en vaciarProducto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Inserta un registro en la tabla post_vendidos para un producto vendido.
     *
     * @param int $id_producto ID del producto vendido.
     * @param int $id_usuario ID del usuario comprador.
     * @param string $fecha_compra Fecha de la compra (formato 'YYYY-MM-DD' o similar).
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */
    public function agregarVendido($id_producto, $id_usuario, $fecha_compra){
        try {
            return $this->query("INSERT INTO post_vendidos (id_Post, id_Comprador, Fecha) VALUES ({$id_producto}, {$id_usuario}, '{$fecha_compra}');");
        } catch (PDOException $e) {
            error_log("Error en agregarVendido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los productos de un usuario específico.
     *
     * @param int $id_usuario ID del usuario vendedor.
     * @return array Lista de productos asociados al usuario, vacío en caso de error.
     */
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

    /**
     * Obtiene la cantidad total de productos de un usuario.
     *
     * @param int $id_usuario ID del usuario vendedor.
     * @return int Número de productos que tiene el usuario, 0 en caso de error.
     */
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

    /**
     * Obtiene una lista paginada de productos de un usuario.
     *
     * @param int $id_usuario ID del usuario vendedor.
     * @param int $inicio Índice de inicio para la paginación.
     * @param int $limit Cantidad máxima de resultados.
     * @return array Lista de productos paginada, vacío en caso de error.
     */
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

    /**
     * Obtiene todas las compras hechas por un usuario.
     *
     * @param int $id_usuario ID del usuario comprador.
     * @return array Lista de compras realizadas por el usuario, vacío en caso de error.
     */
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

    /**
     * Obtiene la cantidad total de compras hechas por un usuario.
     *
     * @param int $id_usuario ID del usuario comprador.
     * @return int Número total de compras, 0 en caso de error.
     */
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

    /**
     * Obtiene una lista paginada de compras hechas por un usuario.
     *
     * @param int $id_usuario ID del usuario comprador.
     * @param int $inicio Índice de inicio para la paginación.
     * @param int $limit Cantidad máxima de resultados.
     * @return array Lista paginada de compras, vacío en caso de error.
     */
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

    /**
     * Busca registros en la tabla donde el título coincida parcialmente con el texto de búsqueda.
     *
     * @param string $textoBusqueda Texto a buscar en la columna Titulo.
     * @param int $inicio Índice de inicio para paginación.
     * @param int $limit Cantidad máxima de resultados a devolver.
     * @return array Lista de resultados que coinciden con la búsqueda, vacío en caso de error.
     */
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

    /**
     * Cuenta el número total de registros donde el título coincida parcialmente con el texto de búsqueda.
     *
     * @param string $textoBusqueda Texto a buscar en la columna Titulo.
     * @return int Número total de registros coincidentes, 0 en caso de error.
     */
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

    /**
     * Guarda una imagen subida en la carpeta de productos, validando tamaño y formato.
     *
     * @param array $imagen Arreglo con información de la imagen subida (ej. $_FILES['imagen']).
     * @return string|void Retorna el nombre único generado para la imagen en caso de éxito. Termina ejecución y retorna JSON de error en caso de fallo.
     */
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

    /**
     * Elimina un archivo de imagen en el sistema de archivos si existe.
     *
     * @param string $rutaImagen Ruta absoluta o relativa del archivo de imagen.
     * @return bool True si el archivo fue eliminado, false si no existe o si ocurrió un error.
     */
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