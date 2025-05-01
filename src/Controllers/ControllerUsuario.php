<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Juego;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Usuario.
 */
class ControllerUsuario {
    /**
     * @var Usuario Instancia del modelo Usuario.
     */
    private $usuario;

    /**
     * Constructor de la clase ControllerUsuario.
     * Inicializa una nueva instancia del modelo Usuario.
     */
    public function __construct() {
        $this->usuario = new Usuario();
    }

    /**
     * Obtiene todos los usuarios.
     *
     * @return void
     */
    public function getAllUsuarios(): void {
        $this->usuario->getAll();
        require_once '../src/Views/usuarios.php';
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id ID del usuario a obtener.
     * @return void
     */
    public function getUsuarioById($id): void {
        $this->usuario->getById($id);
        require_once '../src/Views/usuarios.php';
    }

    /**
     * Actualiza un usuario existente.
     *
     * @param int $id ID del usuario a actualizar.
     * @param array $data Datos actualizados del usuario.
     * @return void
     */
    public function updateUsuario($id, $data): void {
        $this->usuario->update(id: $id, data: $data);
        require_once '../src/Views/usuarios.php';
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id ID del usuario a eliminar.
     * @return void
     */
    public function deleteUsuario($id): void {
        $this->usuario->delete($id);
        require_once '../src/Views/usuarios.php';
    }

    public function perfil_listas(): void {
        $perfil = ["nick" => "Keyxion", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "wishlist"=>[32, 39, 750, 4639, 9767], "playing"=>[32, 39, 750, 4639, 9767], "completed"=>[32, 39, 750, 4639, 9767]];

        require_once __DIR__.'/../Views/perfil.php';
    }

    public function lista(): void {
        $perfil = ["nick" => "Keyxion", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "wishlist"=>[32, 39, 750, 4639, 9767]];
        require_once __DIR__.'/../Views/listas_perfil.php';
    }
}