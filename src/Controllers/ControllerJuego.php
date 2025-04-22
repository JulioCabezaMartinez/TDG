<?php

namespace App\Controllers;

use App\Models\Juego;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Juego.
 */
class ControllerJuego {
    /**
     * @var Juego Instancia del modelo Juego.
     */
    private $juego;

    /**
     * Constructor de la clase ControllerJuego.
     * Inicializa una nueva instancia del modelo Juego.
     */
    public function __construct() {
        $this->juego = new Juego();
    }

    /**
     * Obtiene todos los juegos.
     *
     * @return void
     */
    public function getAllJuegos(): void {
        $this->juego->getAll();
        require_once '../src/Views/juegos.php';
    }

    public function getNewJuegos(): void {
        $this->juego->getNew();
        require_once '../src/Views/juegos.php';
    }

    /**
     * Obtiene un juego por su ID.
     *
     * @param int $id ID del juego a obtener.
     * @return void
     */
    public function getJuegoById($id): void {
        $this->juego->getById($id);
        require_once '../src/Views/juegos.php';
    }

    /**
     * Actualiza un juego existente.
     *
     * @param int $id ID del juego a actualizar.
     * @param array $data Datos actualizados del juego.
     * @return void
     */
    public function updateJuego($id, $data): void {
        $this->juego->update(id: $id, data: $data);
        require_once '../src/Views/juegos.php';
    }

    /**
     * Elimina un juego por su ID.
     *
     * @param int $id ID del juego a eliminar.
     * @return void
     */
    public function deleteJuego($id): void {
        $this->juego->delete($id);
        require_once '../src/Views/juegos.php';
    }
}