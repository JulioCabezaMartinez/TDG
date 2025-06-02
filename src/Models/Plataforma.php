<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Traits\BusquedaAlfa;
use PDO;
use PDOException;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de plataformas.
 */
class Plataforma extends EmptyModel {

    use BusquedaAlfa;

    /**
     * Constructor de la clase Plataforma.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('plataformas', 'id');
    }

    public function rellenarBDJuegosPlataforma($id_juego, $id_plataforma): void {
        try {
            $sql = "INSERT INTO plataformas_juego (id_juego, id_plataforma) VALUES (?, ?)";
            $this->query($sql, [$id_juego, $id_plataforma]);
        } catch (PDOException $e) {
            error_log("Error al insertar en plataformas_juego: " . $e->getMessage());
        }
        
    }

    public function rellenarBDPlataformas($id_genero, $nombre) {
        try {
            $sql = "INSERT INTO plataformas (id, Nombre) VALUES (?, ?)";
            $this->query($sql, [$id_genero, $nombre]);
        } catch (PDOException $e) {
            error_log("Error al insertar en plataformas: " . $e->getMessage());
        }
    }

    public function getPlataformasJuegobyId($id_juego) {
        try {
            $sql = "SELECT p.Nombre FROM plataformas p
                    JOIN plataformas_juego pj ON p.id = pj.id_plataforma
                    WHERE pj.id_juego = ?";
            return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener nombres de plataformas del juego: " . $e->getMessage());
            return [];
        }
    }

    public function getPlataformasIDJuegobyId($id_juego) {
        try {
            $sql = "SELECT p.id FROM plataformas p
                    JOIN plataformas_juego pj ON p.id = pj.id_plataforma
                    WHERE pj.id_juego = ?";
            return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener IDs de plataformas del juego: " . $e->getMessage());
            return [];
        }
    }
}
?>