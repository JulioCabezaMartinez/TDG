<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Traits\BusquedaAlfa;
use PDO;
use PDOException;
use Exception;

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

    /**
     * Borra todas las plataformas asociadas a un juego.
     *
     * @param int $id_juego ID del juego.
     * @return bool Resultado de la ejecución (true si tuvo éxito).
     * @throws Exception Si ocurre un error en la consulta.
     */
    public function borrarPlataformasJuego($id_juego){
        try {
            $sql = "DELETE FROM plataformas_juego WHERE id_juego=:id_juego";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id_juego", $id_juego, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al borrar plataformas_juego: " . $e->getMessage());
        }
    }

    /**
     * Inserta una plataforma para un juego específico.
     *
     * @param int $id_juego ID del juego.
     * @param int $id_plataforma ID de la plataforma.
     * @return bool Resultado de la ejecución (true si tuvo éxito).
     * @throws Exception Si ocurre un error en la consulta.
     */
    public function insertarPlataformasJuego($id_juego, $id_plataforma){
        try {
            $sql = "INSERT INTO plataformas_juego (id_plataforma, id_juego) VALUES (:id_plataforma, :id_juego);";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id_juego", $id_juego, PDO::PARAM_INT);
            $stmt->bindParam("id_plataforma", $id_plataforma, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar plataformas_juego: " . $e->getMessage());
        }
    }

    /**
     * Inserta una relación juego-plataforma en la base de datos.
     *
     * @param int $id_juego ID del juego.
     * @param int $id_plataforma ID de la plataforma.
     * @return void
     */
    public function rellenarBDJuegosPlataforma($id_juego, $id_plataforma): void {
        try {
            $sql = "INSERT INTO plataformas_juego (id_juego, id_plataforma) VALUES (?, ?)";
            $this->query($sql, [$id_juego, $id_plataforma]);
        } catch (PDOException $e) {
            error_log("Error al insertar en plataformas_juego: " . $e->getMessage());
        }
        
    }

    /**
     * Inserta una plataforma en la base de datos.
     *
     * @param int $id_genero ID de la plataforma.
     * @param string $nombre Nombre de la plataforma.
     * @return void
     */
    public function rellenarBDPlataformas($id_genero, $nombre) {
        try {
            $sql = "INSERT INTO plataformas (id, Nombre) VALUES (?, ?)";
            $this->query($sql, [$id_genero, $nombre]);
        } catch (PDOException $e) {
            error_log("Error al insertar en plataformas: " . $e->getMessage());
        }
    }

    /**
     * Obtiene los nombres de las plataformas asociadas a un juego.
     *
     * @param int $id_juego ID del juego.
     * @return array Array de arrays asociativos con el campo 'Nombre'.
     */
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

    /**
     * Obtiene los IDs de las plataformas asociadas a un juego.
     *
     * @param int $id_juego ID del juego.
     * @return array Array de arrays asociativos con el campo 'id'.
     */
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