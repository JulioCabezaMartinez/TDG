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

    /**
     * Obtiene todos los juegos.
     *
     * @return void
     */
    public function getAllJuegos(): void {
        $this->juego->getAll();
        require_once '../src/Views/juegos.php';
    }

    public function getNewJuegos(): void {
        $this->juego->getNew();
        require_once '../src/Views/juegos.php';
    }

    /**
     * Obtiene un juego por su ID.
     *
     * @param int $id ID del juego a obtener.
     * @return void
     */
    public function getJuegoById($id): void {
        $this->juego->getById($id);
        require_once '../src/Views/juegos.php';
    }

    /**
     * Actualiza un juego existente.
     *
     * @param int $id ID del juego a actualizar.
     * @param array $data Datos actualizados del juego.
     * @return void
     */
    public function updateJuego($id, $data): void {
        $this->juego->update(id: $id, data: $data);
        require_once '../src/Views/juegos.php';
    }

    /**
     * Elimina un juego por su ID.
     *
     * @param int $id ID del juego a eliminar.
     * @return void
     */
    public function deleteJuego($id): void {
        $this->juego->delete($id);
        require_once '../src/Views/juegos.php';
    }

    public function lista_juegos(): void {
        $lista = new Lista();

        $id_usuario = 1; // $_GET['id_usuario']; // Obtener el ID del usuario desde la sesión.
        $listas_usuario = $lista->getListasUsuario($id_usuario); // Obtener las listas del usuario.

        $juego = new Juego();

        // Paginación provisional. Cambiar por la paginación real por AJAX.
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtener la página actual desde la URL. Si no existe, se establece en 1.
        $limite = 5; // Número de juegos por página.
        $total_juegos = $juego->getCount(); // Obtener el total de juegos en la base de datos.
        $total_paginas = ceil($total_juegos / $limite); // Calcular el total de páginas.

        if($pagina<=0){
            $inicio = 0;
            $pagina = 1;
        }else{
            $inicio = ($pagina-1)*$limite; // 5 juegos por página. 
        }
        $juegos = $juego->getListGames($inicio, $limite); // Obtener 10 juegos. Paginación falta
        require_once 'src/Views/lista_juegos.php'; // Cargar la vista de juegos
    }

    public function view_juego(): void {
        $id_juego = $_GET['id_juego'] ?? 2093; // Obtener el ID del juego desde la URL.
        $juego = new Juego();
        $genero = new Genero();
        $plataforma = new Plataforma();
        
        $juego = $juego->getById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        $generos = $genero->getGenerosJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar
        $plataformas = $plataforma->getPlataformasJuegoById($id_juego); // Cambiar el ID por el del juego que quieras mostrar

        require_once 'src/Views/juego.php'; // Cargar la vista del juego
    }
}