<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Interfaces\BusquedaAdmin;

use PDO;
use PDOException;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de reviews.
 */
class Review extends EmptyModel implements BusquedaAdmin {
    /**
     * Constructor de la clase Review.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('review', 'id');
    }

    /**
     * Obtiene todas las reviews de un juego con paginación.
     *
     * @param int $id_juego ID del juego.
     * @param int $inicio Índice inicial para la paginación.
     * @param int $limit Cantidad máxima de reviews a obtener.
     * @return array Lista de reviews como arrays asociativos.
     */
    public function getAllReviewsJuego($id_juego, $inicio, $limit) {
        try {
            $sql = "SELECT * FROM review WHERE id_Juego = :id_juego ORDER BY id DESC LIMIT :inicio, :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getAllReviewsJuego: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cuenta el total de reviews de un juego.
     *
     * @param int $id_juego ID del juego.
     * @return int Cantidad total de reviews.
     */
    public function countAllReviewsJuego($id_juego){
        try {
            $sql = "SELECT COUNT(*) FROM review WHERE id_Juego = :id_juego";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en countAllReviewsJuego: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtiene las últimas 3 reviews en general.
     *
     * @return array Lista de las últimas 3 reviews.
     */
    public function ultimasReviews(){
        try {
            return $this->query("SELECT * FROM review ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ultimasReviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las últimas 3 reviews de un juego específico.
     *
     * @param int $id_juego ID del juego.
     * @return array Lista de las últimas 3 reviews del juego.
     */
    public function ultimasReviewsJuego($id_juego){
        try {
            $sql = "SELECT * FROM review WHERE id_Juego = :id_juego ORDER BY id DESC LIMIT 3";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ultimasReviewsJuego: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca reviews en modo administrador con paginación y filtrado por nombre de juego.
     *
     * @param string $textoBusqueda Texto para buscar en el nombre del juego (debe incluir % para LIKE).
     * @param int $inicio Índice inicial para la paginación.
     * @param int $limit Cantidad máxima de resultados a obtener.
     * @return array Lista de reviews que coinciden con la búsqueda.
     */
    public function buscarAdmin($textoBusqueda, $inicio, $limit){

        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_juego IN (SELECT id FROM juegos WHERE Nombre LIKE :textoBusqueda) LIMIT {$inicio}, {$limit}";
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
     * Cuenta la cantidad de reviews que coinciden con la búsqueda en modo administrador.
     *
     * @param string $textoBusqueda Texto para buscar en el nombre del juego (debe incluir % para LIKE).
     * @return int Cantidad total de resultados que coinciden.
     */
    public function buscarAdminCount($textoBusqueda){

        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id_juego IN (SELECT id FROM juegos WHERE Nombre LIKE :textoBusqueda)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en buscarAdminCount: " . $e->getMessage());
            return 0;
        }
    }
}
?>