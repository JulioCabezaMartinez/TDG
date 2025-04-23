<?php

namespace App\Models;

use PDO;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de géneros.
 */
class Genero extends EmptyModel {
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

    public function getGenerosJuego($id) {
        $sql = "SELECT * FROM generos WHERE id = ?";
        return $this->query($sql, [$id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rellenarBDJuegosGeneros($id_juego, $id_genero): void {
        $sql = "INSERT INTO generos_juego (id_juego, id_genero) VALUES (?, ?)";
        $this->query($sql, [$id_juego, $id_genero]);
        
    }

    public function rellenarBDGeneros($id_genero, $nombre) {
        $sql = "INSERT INTO generos (id, Nombre) VALUES (?, ?)";
        $this->query($sql, [$id_genero, $nombre]);
    }

    public function getGenerosJuegobyId($id_juego) {
        $sql = "SELECT g.Nombre FROM generos g
                JOIN generos_juego gj ON g.id = gj.id_genero
                WHERE gj.id_juego = ?";
        return $this->query($sql, [$id_juego])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>