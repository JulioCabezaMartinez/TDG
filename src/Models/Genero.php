<?php

namespace App\Models;

use App\Core\EmptyModel;

use App\Traits\BusquedaAlfa;
use PDO;
use PDOException;
use Exception;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de géneros.
 */
class Genero extends EmptyModel {

    use BusquedaAlfa;

    public function __construct() {
        parent::__construct('generos', 'id');
    }

    public function borrarGenerosJuego($id_juego){
        try {
            $sql = "DELETE FROM generos_juego WHERE id_juego=:id_juego";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id_juego", $id_juego, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al borrar generos_juego: " . $e->getMessage());
        }
    }

    public function insertarGenerosJuego($id_juego, $id_genero){
        try {
            $sql = "INSERT INTO generos_juego (id_genero, id_juego) VALUES (:id_genero, :id_juego);";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id_juego", $id_juego, PDO::PARAM_INT);
            $stmt->bindParam("id_genero", $id_genero, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar generos_juego: " . $e->getMessage());
        }
    }

    public function rellenarBDJuegosGeneros($id_juego, $id_genero): void {
        try {
            $sql = "INSERT INTO generos_juego (id_juego, id_genero) VALUES (?, ?)";
            $this->query($sql, [$id_juego, $id_genero]);
        } catch (PDOException $e) {
            throw new Exception("Error al rellenar generos_juego: " . $e->getMessage());
        }
    }

    public function rellenarBDGeneros($id_genero, $nombre) {
        try {
            $sql = "INSERT INTO generos (id, Nombre) VALUES (?, ?)";
            $this->query($sql, [$id_genero, $nombre]);
        } catch (PDOException $e) {
            throw new Exception("Error al rellenar tabla generos: " . $e->getMessage());
        }
    }

    public function getGenerosJuegoById($id_juego) {
        try {
            $sql = "SELECT g.Nombre FROM generos g
                    JOIN generos_juego gj ON g.id = gj.id_genero
                    WHERE gj.id_juego = ?";
            return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener géneros del juego: " . $e->getMessage());
        }
    }

    public function getGenerosIDJuegoById($id_juego) {
        try {
            $sql = "SELECT g.id FROM generos g
                    JOIN generos_juego gj ON g.id = gj.id_genero
                    WHERE gj.id_juego = ?";
            return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener IDs de géneros: " . $e->getMessage());
        }
    }
}

?>