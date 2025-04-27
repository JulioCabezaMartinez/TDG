<?php

namespace App\Models;

use App\Core\EmptyModel;
use PDO;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de plataformas.
 */
class Plataforma extends EmptyModel {
    /**
     * Constructor de la clase Plataforma.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('plataformas', 'id');
    }

    public function rellenarBDJuegosPlataforma($id_juego, $id_plataforma): void {
        $sql = "INSERT INTO plataformas_juego (id_juego, id_plataforma) VALUES (?, ?)";
        $this->query($sql, [$id_juego, $id_plataforma]);
        
    }

    public function rellenarBDPlataformas($id_genero, $nombre) {
        $sql = "INSERT INTO plataformas (id, Nombre) VALUES (?, ?)";
        $this->query($sql, [$id_genero, $nombre]);
    }

    public function getPlataformasJuegobyId($id_juego) {
        $sql = "SELECT p.Nombre FROM plataformas p
                JOIN plataformas_juego pj ON p.id = pj.id_plataforma
                WHERE pj.id_juego = ?";
        return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>