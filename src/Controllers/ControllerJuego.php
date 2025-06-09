<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Genero;
use App\Models\Plataforma;
use App\Models\Review;

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

    /**
     * Muestra la lista de juegos disponibles.
     *
     * Si se ha enviado una búsqueda por GET, la almacena en una variable para su uso en la vista.
     * Luego carga la vista correspondiente a la lista de juegos (`lista_juegos.php`).
     *
     * @return void
     */
    public function lista_juegos(): void {
        if(!empty($_GET)){
            $juegoBusqueda=$_GET["juego"];
        }
        include_once 'src/Views/lista_juegos.php'; // Cargar la vista de juegos
    }

    /**
     * Muestra la vista detallada de un juego específico, incluyendo sus géneros, plataformas y reviews.
     *
     * Obtiene el ID del juego desde la URL (GET), y luego recupera:
     * - Los datos del juego,
     * - Los géneros asociados,
     * - Las plataformas compatibles,
     * - Las últimas reviews del juego.
     *
     * Finalmente, incluye la vista del juego (`juego.php`) con todos los datos preparados.
     *
     * @return void
     */
    public function view_juego(): void {
        $id_juego = $_GET['juego']; // Obtener el ID del juego desde la URL.
        $juego = new Juego();
        $genero = new Genero();
        $plataforma = new Plataforma();
        $reviewDB=new Review();

        $juego = $juego->getById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        $lista_reviews=$reviewDB->ultimasReviewsJuego($id_juego);

        $generos = $genero->getGenerosJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar
        $plataformas = $plataforma->getPlataformasJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        include_once 'src/Views/juego.php'; // Cargar la vista del juego
    }
}