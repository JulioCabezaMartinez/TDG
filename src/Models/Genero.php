<?php

namespace App\Models;

use App\Core\EmptyModel;

use App\Traits\BusquedaAlfa;
use PDO;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de géneros.
 */
class Genero extends EmptyModel {

    use BusquedaAlfa;

    /**
     * Constructor de la clase Genero.
     * Configura la tabla y la clave primaria asociadas al modelo.
     */
    public function __construct() {
        parent::__construct('generos', 'id');
    }

    public function createGenero($nombre) {
        $sql = "INSERT INTO generos (Nombre) VALUES (?)";
        $this->query($sql, [$nombre]);
    }

    public function rellenarBDJuegosGeneros($id_juego, $id_genero): void {
        $sql = "INSERT INTO generos_juego (id_juego, id_genero) VALUES (?, ?)";
        $this->query($sql, [$id_juego, $id_genero]);
        
    }

    public function rellenarBDGeneros($id_genero, $nombre) {
        $sql = "INSERT INTO generos (id, Nombre) VALUES (?, ?)";
        $this->query($sql, [$id_genero, $nombre]);
    }

    public function getGenerosJuegoById($id_juego) {
        $sql = "SELECT g.Nombre FROM generos g
                JOIN generos_juego gj ON g.id = gj.id_genero
                WHERE gj.id_juego = ?";
        return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenerosIDJuegoById($id_juego) {
        $sql = "SELECT g.id FROM generos g
                JOIN generos_juego gj ON g.id = gj.id_genero
                WHERE gj.id_juego = ?";
        return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>