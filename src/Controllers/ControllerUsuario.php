<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Lista;
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

    public function perfil_listas(): void {
        $usuarioDB = new Usuario();

        $perfil = $usuarioDB->getById($_SESSION['usuarioActivo']);

        include_once __DIR__.'/../Views/perfil.php';
    }

    public function lista(): void {
        $perfil = ["nick" => "Keyxion", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "wishlist"=>[32, 39, 750, 4639, 9767]];
        include_once __DIR__.'/../Views/listas_perfil.php';
    }

    public function ventas_perfil(): void {
        $usuarioDB = new Usuario();
        $perfil = $usuarioDB->getById($_SESSION['usuarioActivo']);
        // $ventas = $usuarioDB->getVentasByUserId($perfil['id']);
        $perfil['Imagen_usuario'] = $perfil['Imagen_usuario'] ?? 'default.png'; // Aseguramos que haya una imagen por defecto
        include_once __DIR__.'/../Views/ventas_perfil.php';
    }
}