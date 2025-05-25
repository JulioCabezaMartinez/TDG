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
        $usuarioDB=new Usuario();
        $listaDB=new Lista();
        $juegoDB=new Juego();
        $perfil = $usuarioDB->getById($_SESSION['usuarioActivo']);

        // LLamada a la Base de datos para Wishlist
        $perfil['wishlist'] = $listaDB->getUserLists($perfil['id'], "wishlist");

        $whislist=[];
        foreach($perfil["wishlist"] as $id){
            $whislist[$id]=$juegoDB->getById($id);
        }
        $perfil["wishlist"]=$whislist;

        // LLamada a la Base de datos para Completed
        $perfil['completed'] = $listaDB->getUserLists($perfil['id'], "completed");

        $completed=[];
        foreach($perfil["completed"] as $id){
            $completed[$id]=$juegoDB->getById($id);
        }
        $perfil["completed"]=$completed;

        // LLamada a la Base de datos para Playing
        $perfil['playing'] = $listaDB->getUserLists($perfil['id'], "playing");

        $playing=[];
        foreach($perfil["playing"] as $id){
            $playing[$id]=$juegoDB->getById($id);
        }
        $perfil["playing"]=$playing;

        // LLamada a la Base de datos para Backlog
        $perfil['backlog'] = $listaDB->getUserLists($perfil['id'], "backlog");

        $backlog=[];
        foreach($perfil["backlog"] as $id){
            $backlog[$id]=$juegoDB->getById($id);
        }
        $perfil["backlog"]=$backlog;

        include_once __DIR__.'/../Views/perfil.php';
    }

    public function lista(): void {
        $perfil = ["nick" => "Keyxion", "background_image" => "https://media.rawg.io/media/games/21a/21ad672cedee9b4378abb6c2d2e626ee.jpg", "wishlist"=>[32, 39, 750, 4639, 9767]];
        include_once __DIR__.'/../Views/listas_perfil.php';
    }
}