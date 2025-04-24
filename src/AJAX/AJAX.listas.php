<?php

require_once '../../vendor/autoload.php'; // Autoload de Composer para pruebas. En la version debe de ir en el Index.php.

use App\Models\Juego;
use App\Models\Lista;


$mode=$_POST['mode'] ?? null;

if(!$mode){
    echo "Error del Servidor 1: No se ha podido procesar la solicitud.";
}else{
    switch($mode){
        case 'add_juego_lista':
            
            $id_juego = $_POST['id_juego'] ?? null;
            $lista = $_POST['lista'] ?? null;
            $id_usuario = 1;//$_SESSION['id_usuario'] ?? null; // Obtener el ID del usuario desde la sesión.

            $nombre_lista= match($lista){
                'wish' => 'wishlist',
                'back' => 'backlog',
                'comp' => 'completed',
                'play' => 'playing',
                default => null
            };

            if($id_juego && $lista && $id_usuario){
                $lista_bd = new Lista();

                if($lista_bd->addJuegotoLista(id_Juego: $id_juego, lista: $lista, id_user: $id_usuario)){ // Agregar el juego a la lista.

                    echo "Juego añadido a la lista {$nombre_lista} correctamente.";

                }else{

                    echo "Error: No se ha podido añadir el juego a la lista.";

                }
            }else{
                echo "Error: Datos incompletos.";
            }
            break;
        default:
            echo "Error del Servidor 2: No se ha podido procesar la solicitud.";
    }
}