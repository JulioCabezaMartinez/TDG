<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Juego;
use App\Models\Lista;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Usuario.
 */
class ControllerAJAX {

    public function lista_juegos(){
        
        $juegoDB=new Juego();
        $listaDB=new Lista();

        $id_usuario = 1; // $_GET['id_usuario']; // Obtener el ID del usuario desde la sesión.
        $listas_usuario = $listaDB->getListasUsuario($id_usuario); // Obtener las listas del usuario.

        $total_juegos = $juegoDB->getCount();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];

        $total_paginas = ceil($total_juegos / $limite);
        
        $juegos = $juegoDB->getListGames($inicio, $limite); // Obtener 10 juegos

        foreach ($juegos as &$juego) {

            // Booleanos para comprobar si el juego está en las listas del usuario.
            $wishlist = false;
            $backlog = false;
            $completed = false;
            $playing = false;
        
            $listas_juego = $listaDB->compruebaJuegoLista($juego['id'], $listas_usuario); // Comprobar si el juego está en las listas del usuario.
            foreach ($listas_juego as $lista_usuario) {

                switch ($listaDB->getTipoLista($lista_usuario)) {
                    case 1:
                        $wishlist = true;
                        break;
                    case 2:
                        $completed = true;
                        break;
                    case 3:
                        $playing = true;
                        break;
                    case 4:
                        $backlog = true;
                        break;
                }
            }
            $juego["estados"]=[$wishlist, $backlog, $completed, $playing];
        }



        echo json_encode(["juegos"=>$juegos, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }
}