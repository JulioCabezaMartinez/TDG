<?php

namespace App\Controllers;

use App\Models\Lista;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Lista.
 */
class ControllerLista {
    /**
     * @var Lista Instancia del modelo Lista.
     */
    private $lista;

    /**
     * Constructor de la clase ControllerLista.
     * Inicializa una nueva instancia del modelo Lista.
     */
    public function __construct() {
        $this->lista = new Lista();
    }

    /**
     * Obtiene todas las listas.
     *
     * @return void
     */
    public function getAllListas(): void {
        $this->lista->getAll();
        require_once '../src/Views/listas.php';
    }

    /**
     * Obtiene una lista por su ID.
     *
     * @param int $id ID de la lista a obtener.
     * @return void
     */
    public function getListaById($id): void {
        $this->lista->getById($id);
        require_once '../src/Views/listas.php';
    }

    /**
     * Actualiza una lista existente.
     *
     * @param int $id ID de la lista a actualizar.
     * @param array $data Datos actualizados de la lista.
     * @return void
     */
    public function updateLista($id, $data): void {
        $this->lista->update(id: $id, data: $data);
        require_once '../src/Views/listas.php';
    }

    /**
     * Elimina una lista por su ID.
     *
     * @param int $id ID de la lista a eliminar.
     * @return void
     */
    public function deleteLista($id): void {
        $this->lista->delete($id);
        require_once '../src/Views/listas.php';
    }
}