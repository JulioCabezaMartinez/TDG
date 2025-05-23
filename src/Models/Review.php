<?php

namespace App\Models;

use App\Core\EmptyModel;
use PDO;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de reviews.
 */
class Review extends EmptyModel {
    /**
     * Constructor de la clase Review.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('review', 'id_Review');
    }

    public function getAllReviewsJuego($id_juego){
        $sql="Select * from review where id_Juego=:id_juego";
        $params=[":id_juego"=>$id_juego];
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ultimasReviews(){
        return $this->query("SELECT * from review ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>