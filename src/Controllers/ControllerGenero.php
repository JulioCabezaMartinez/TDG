<?php

namespace App\Controllers;

use App\Models\Genero;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Genero.
 */
class ControllerGenero {
    /**
     * @var Genero Instancia del modelo Genero.
     */
    private $genero;

    /**
     * Constructor de la clase ControllerGenero.
     * Inicializa una nueva instancia del modelo Genero.
     */
    public function __construct() {
        $this->genero = new Genero();
    }

    /**
     * Obtiene todos los géneros.
     *
     * @return void
     */
    public function getAllGeneros(): void {
        $this->genero->getAll();
        require_once '../src/Views/generos.php';
    }

    /**
     * Obtiene un género por su ID.
     *
     * @param int $id ID del género a obtener.
     * @return void
     */
    public function getGeneroById($id): void {
        $this->genero->getById($id);
        require_once '../src/Views/generos.php';
    }

    /**
     * Actualiza un género existente.
     *
     * @param int $id ID del género a actualizar.
     * @param array $data Datos actualizados del género.
     * @return void
     */
    public function updateGenero($id, $data): void {
        $this->genero->update(id: $id, data: $data);
        require_once '../src/Views/generos.php';
    }

    /**
     * Elimina un género por su ID.
     *
     * @param int $id ID del género a eliminar.
     * @return void
     */
    public function deleteGenero($id): void {
        $this->genero->delete($id);
        require_once '../src/Views/generos.php';
    }
}