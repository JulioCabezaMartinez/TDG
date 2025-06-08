<?php

namespace App\Controllers;

use App\Core\Security;
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

        if (empty($_SESSION)) {
            Security::closeSession();
        }

        $usuarioDB = new Usuario();

        $perfil = $usuarioDB->getById($_SESSION['usuarioActivo']);
        
        $datos=$usuarioDB->listaColumnas();
        $columnas=[];
        foreach($datos as $dato){
            array_push($columnas, $dato["Field"]);
        }

        include_once __DIR__.'/../Views/perfil.php';
    }

    public function ventas_perfil(): void {

        if (empty($_SESSION)) {
            Security::closeSession();
        }

        $usuarioDB = new Usuario();
        $perfil = $usuarioDB->getById($_SESSION['usuarioActivo']);
        // $ventas = $usuarioDB->getVentasByUserId($perfil['id']);
        $perfil['Imagen_usuario'] = $perfil['Imagen_usuario'] ?? 'default.png'; // Aseguramos que haya una imagen por defecto
        include_once __DIR__.'/../Views/ventas_perfil.php';
    }

    public function conseguirPremium(){

        if(empty($_SESSION)) {
            Security::closeSession();
        }

        $usuarioBD=new Usuario();

        $usuario=$usuarioBD->getById($_SESSION["usuarioActivo"]);

        if($usuario["Premium"]==1){
            header("Location:/perfil");
        }

        include __DIR__. "/../Views/conseguirPremium.php";
        
    }
}