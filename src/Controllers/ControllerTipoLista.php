<?php

namespace App\Controllers;

use App\Models\Tipo_Lista;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Tipo_Lista.
 */
class ControllerTipoLista {
    /**
     * @var Tipo_Lista Instancia del modelo Tipo_Lista.
     */
    private $tipoLista;

    /**
     * Constructor de la clase ControllerTipoLista.
     * Inicializa una nueva instancia del modelo Tipo_Lista.
     */
    public function __construct() {
        $this->tipoLista = new Tipo_Lista();
    }

    /**
     * Obtiene todos los tipos de lista.
     *
     * @return void
     */
    public function getAllTiposLista(): void {
        $this->tipoLista->getAll();
        require_once 'src/Views/tipos_lista.php';
    }

    /**
     * Obtiene un tipo de lista por su ID.
     *
     * @param int $id ID del tipo de lista a obtener.
     * @return void
     */
    public function getTipoListaById($id): void {
        $this->tipoLista->getById($id);
        require_once 'src/Views/tipos_lista.php';
    }

    /**
     * Actualiza un tipo de lista existente.
     *
     * @param int $id ID del tipo de lista a actualizar.
     * @param array $data Datos actualizados del tipo de lista.
     * @return void
     */
    public function updateTipoLista($id, $data): void {
        $this->tipoLista->update(id: $id, data: $data);
        require_once 'src/Views/tipos_lista.php';
    }

    /**
     * Elimina un tipo de lista por su ID.
     *
     * @param int $id ID del tipo de lista a eliminar.
     * @return void
     */
    public function deleteTipoLista($id): void {
        $this->tipoLista->delete($id);
        require_once 'src/Views/tipos_lista.php';
    }
}