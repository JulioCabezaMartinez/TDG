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

    /**
     * Constructor de la clase Genero.
     * Llama al constructor padre con el nombre de la tabla 'generos' y la clave primaria 'id'.
     */
    public function __construct() {
        parent::__construct('generos', 'id');
    }

    /**
     * Elimina todos los registros de géneros asociados a un juego dado.
     *
     * @param int $id_juego ID del juego cuyo género se desea eliminar.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     * @throws Exception Si ocurre un error con la base de datos.
     */
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

    /**
     * Inserta una asociación entre un juego y un género en la tabla generos_juego.
     *
     * @param int $id_juego ID del juego.
     * @param int $id_genero ID del género.
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     * @throws Exception Si ocurre un error con la base de datos.
     */
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

    /**
     * Inserta una asociación entre un juego y un género usando placeholders anónimos.
     *
     * @param int $id_juego ID del juego.
     * @param int $id_genero ID del género.
     * @return void
     * @throws Exception Si ocurre un error con la base de datos.
     */
    public function rellenarBDJuegosGeneros($id_juego, $id_genero): void {
        try {
            $sql = "INSERT INTO generos_juego (id_juego, id_genero) VALUES (?, ?)";
            $this->query($sql, [$id_juego, $id_genero]);
        } catch (PDOException $e) {
            throw new Exception("Error al rellenar generos_juego: " . $e->getMessage());
        }
    }

    /**
     * Inserta un nuevo género en la tabla generos.
     *
     * @param int $id_genero ID del género.
     * @param string $nombre Nombre del género.
     * @return void
     * @throws Exception Si ocurre un error con la base de datos.
     */
    public function rellenarBDGeneros($id_genero, $nombre) {
        try {
            $sql = "INSERT INTO generos (id, Nombre) VALUES (?, ?)";
            $this->query($sql, [$id_genero, $nombre]);
        } catch (PDOException $e) {
            throw new Exception("Error al rellenar tabla generos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene los nombres de los géneros asociados a un juego dado.
     *
     * @param int $id_juego ID del juego.
     * @return array Lista de géneros (cada uno como arreglo asociativo con clave "Nombre").
     * @throws Exception Si ocurre un error con la base de datos.
     */
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

    /**
     * Obtiene los IDs de los géneros asociados a un juego dado.
     *
     * @param int $id_juego ID del juego.
     * @return array Lista de IDs de géneros (cada uno como arreglo asociativo con clave "id").
     * @throws Exception Si ocurre un error con la base de datos.
     */
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