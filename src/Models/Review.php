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

    public function ultimasReviews(){
        try {
            return $this->query("SELECT * FROM review ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ultimasReviews: " . $e->getMessage());
            return [];
        }
    }

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