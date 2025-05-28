<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Interfaces\BusquedaAdmin;

use PDO;

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
        $sql="Select * from review where id_Juego=:id_juego order by id desc LIMIT :inicio, :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllReviewsJuego($id_juego){
        $sql="Select count(*) from review where id_Juego=:id_juego";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function ultimasReviews(){
        return $this->query("SELECT * from review ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ultimasReviewsJuego($id_juego){
        $sql="Select * from review where id_Juego=:id_juego ORDER BY id DESC LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_juego', $id_juego, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarAdmin($textoBusqueda, $inicio, $limit){

        $sql = "SELECT * FROM {$this->table} WHERE id_juego IN (SELECT id from juegos where Nombre LIKE :textoBusqueda)  LIMIT {$inicio}, {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function buscarAdminCount($textoBusqueda){

        $sql = "SELECT * FROM {$this->table} WHERE id_juego IN (SELECT id from juegos where Nombre LIKE :textoBusqueda)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>