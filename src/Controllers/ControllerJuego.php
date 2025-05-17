<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Genero;
use App\Models\Plataforma;

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

    public function lista_juegos(): void {
        if(!empty($_GET)){
            $juegoBusqueda=$_GET["juego"];
        }
        include_once 'src/Views/lista_juegos.php'; // Cargar la vista de juegos
    }

    public function view_juego(): void {
        $id_juego = $_GET['id'] ?? 2093; // Obtener el ID del juego desde la URL.
        $juego = new Juego();
        $genero = new Genero();
        $plataforma = new Plataforma();
        
        $juego = $juego->getById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        $generos = $genero->getGenerosJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar
        $plataformas = $plataforma->getPlataformasJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        include_once 'src/Views/juego.php'; // Cargar la vista del juego
    }
}