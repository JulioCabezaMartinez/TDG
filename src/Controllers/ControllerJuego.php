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
        // $lista = new Lista();

        // $id_usuario = 1; // $_GET['id_usuario']; // Obtener el ID del usuario desde la sesión.
        // $listas_usuario = $lista->getListasUsuario($id_usuario); // Obtener las listas del usuario.

        $juego = new Juego();

        $pagina = 1; // Obtener la página actual, al inicio, se establece en 1.
        $limite = 5; // Número de juegos por página.
        $total_juegos = $juego->getCount(); // Obtener el total de juegos en la base de datos.
        $total_paginas = ceil($total_juegos / $limite); // Calcular el total de páginas.

        // if($pagina<=0){
        //     $inicio = 0;
        //     $pagina = 1;
        // }else{
        //     $inicio = ($pagina-1)*$limite; // 5 juegos por página. 
        // }

        // $juegos = $juego->getListGames($inicio, $limite); // Obtener 10 juegos

        // foreach ($juegos as &$juego) {

        //     // Booleanos para comprobar si el juego está en las listas del usuario.
        //     $wishlist = false;
        //     $backlog = false;
        //     $completed = false;
        //     $playing = false;
        
        //     $listas_juego = $lista->compruebaJuegoLista($juego['id'], $listas_usuario); // Comprobar si el juego está en las listas del usuario.
        //     foreach ($listas_juego as $lista_usuario) {

        //         switch ($lista->getTipoLista($lista_usuario)) {
        //             case 1:
        //                 $wishlist = true;
        //                 break;
        //             case 2:
        //                 $completed = true;
        //                 break;
        //             case 3:
        //                 $playing = true;
        //                 break;
        //             case 4:
        //                 $backlog = true;
        //                 break;
        //         }
        //     }
        //     $juego["estados"]=[$wishlist, $backlog, $completed, $playing];
        // }

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