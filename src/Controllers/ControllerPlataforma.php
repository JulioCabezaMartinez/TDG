<?php

namespace App\Controllers;

use App\Models\Plataforma;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Plataforma.
 */
class ControllerPlataforma {
    /**
     * @var Plataforma Instancia del modelo Plataforma.
     */
    private $plataforma;

    /**
     * Constructor de la clase ControllerPlataforma.
     * Inicializa una nueva instancia del modelo Plataforma.
     */
    public function __construct() {
        $this->plataforma = new Plataforma();
    }

    /**
     * Obtiene todas las plataformas.
     *
     * @return void
     */
    public function getAllPlataformas(): void {
        $this->plataforma->getAll();
        require_once '../src/Views/plataformas.php';
    }

    /**
     * Obtiene una plataforma por su ID.
     *
     * @param int $id ID de la plataforma a obtener.
     * @return void
     */
    public function getPlataformaById($id): void {
        $this->plataforma->getById($id);
        require_once '../src/Views/plataformas.php';
    }

    /**
     * Actualiza una plataforma existente.
     *
     * @param int $id ID de la plataforma a actualizar.
     * @param array $data Datos actualizados de la plataforma.
     * @return void
     */
    public function updatePlataforma($id, $data): void {
        $this->plataforma->update(id: $id, data: $data);
        require_once '../src/Views/plataformas.php';
    }

    /**
     * Elimina una plataforma por su ID.
     *
     * @param int $id ID de la plataforma a eliminar.
     * @return void
     */
    public function deletePlataforma($id): void {
        $this->plataforma->delete($id);
        require_once '../src/Views/plataformas.php';
    }
}