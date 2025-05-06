<?php
session_start();

require_once '../../vendor/autoload.php'; // Autoload de Composer para pruebas. En la version debe de ir en el Index.php.

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Review;


$mode=$_POST['mode'] ?? null;

if(!$mode){
    echo "Error del Servidor 1: No se ha podido procesar la solicitud.";
}else{
    switch($mode){
        case "add_review":
            $id_juego = $_POST["id_juego"];
            $review = $_POST["review"];
            $id_usuario = 1; //$_SESSION["id_usuario"];

            $reviewDB=new Review();

            if($id_juego && $review && $id_usuario){
                if(!is_bool($reviewDB->create(array(
                    "Contenido" => $review,
                    "id_Escritor" => $id_usuario,
                    "id_Juego" => $id_juego)))
                ){
                    echo "Review insertada con exito";
                }
            }else{
                echo "Error: Datos incompletos";
            }

            break;
        default:
            echo "Error del Servidor 2: No se ha podido procesar la solicitud.";
    }
}