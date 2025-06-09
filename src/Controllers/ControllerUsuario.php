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

    /**
     * Muestra el perfil del usuario activo con sus datos y columnas de la base de datos.
     *
     * Verifica que haya una sesión activa; si no la hay, se cierra la sesión.
     * Recupera los datos del usuario actualmente logueado y la lista de columnas de la tabla `usuarios`
     * para pasarlos a la vista del perfil.
     *
     * @return void
     */
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

    /**
     * Muestra la vista de ventas relacionadas con el perfil del usuario activo.
     *
     * Verifica si hay una sesión activa. Luego obtiene los datos del usuario
     * actual y asegura que siempre haya una imagen de perfil (usa una por defecto si no existe).
     * Carga la vista correspondiente a las ventas del perfil.
     *
     * @return void
     */
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

    /**
     * Muestra la vista para conseguir una cuenta premium, si el usuario aún no es premium.
     *
     * Verifica si hay sesión activa y si el usuario no es premium.
     * Si ya es premium, lo redirige al perfil. En caso contrario, carga la vista
     * con la opción para obtener cuenta premium.
     *
     * @return void
     */
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