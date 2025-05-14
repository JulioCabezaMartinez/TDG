<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Review;
use App\Models\Usuario;

use App\Core\Validators;
use App\Core\Security;

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

    public function lista_review(){
        $id_juego = $_POST["id_juego"];
        $review = $_POST["review"];
        $id_usuario = 1; //$_SESSION["id_usuario"];

        $reviewDB = new Review();

        if ($id_juego && $review && $id_usuario) {
            if (!is_bool($reviewDB->create(array(
                "Contenido" => $review,
                "id_Escritor" => $id_usuario,
                "id_Juego" => $id_juego
            )))) {
                echo json_encode(["result" => "Review insertada con exito"]);
            }
        } else {
            echo json_encode(["result" => "Error: Datos incompletos"]);
        }
    }

    public function addJuegoLista(){
        $id_juego = $_POST['id_juego'] ?? null;
        $lista = $_POST['lista'] ?? null;
        $id_usuario = 1; //$_SESSION['usuarioActivo'] ?? null; // Obtener el ID del usuario desde la sesión.

        $nombre_lista = match ($lista) {
            'wish' => 'wishlist',
            'back' => 'backlog',
            'comp' => 'completed',
            'play' => 'playing',
            default => null
        };

        if ($id_juego && $lista && $id_usuario) {
            $lista_bd = new Lista();

            if ($lista_bd->addJuegoToLista(id_Juego: $id_juego, lista: $lista, id_user: $id_usuario)) { // Agregar el juego a la lista.

                echo json_encode(["result" =>"Juego añadido a la lista {$nombre_lista} correctamente."]);
            } else {

                echo json_encode(["result" =>"Error: No se ha podido añadir el juego a la lista."]);
            }
        } else {
            echo json_encode(["result" =>"Error: Datos incompletos."]);
        }
    }

    public function eliminarJuegoLista(){

        $id_juego = $_POST['id_juego'] ?? null;
        $lista = $_POST['lista'] ?? null;
        $id_usuario = 1; //$_SESSION['usuarioActivo'] ?? null; // Obtener el ID del usuario desde la sesión.

        $nombre_lista = match ($lista) {
            'wish' => 'wishlist',
            'back' => 'backlog',
            'comp' => 'completed',
            'play' => 'playing',
            default => null
        };

        if ($id_juego && $lista && $id_usuario) {
            $lista_bd = new Lista();

            if ($lista_bd->deleteJuegoOfLista(id_Juego: $id_juego, lista: $lista, id_user: $id_usuario)) { // Agregar el juego a la lista.

                echo json_encode(["result" =>"Juego eliminado de la lista {$nombre_lista} correctamente."]);
            } else {

                echo json_encode(["result" =>"Error: No se ha podido eliminar el juego a la lista."]);
            }
        } else {
            echo json_encode(["result" =>"Error: Datos incompletos."]);
        }
    }

    public function registrarUsuario(){
        $usuario = new Usuario();
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $pass = $_POST['password'];
        $nick = $_POST['username'];
        $imagen = $_FILES['imagen_perfil']['name'] ?? null; // Obtener la imagen si se proporciona

        // Lógica para registrar al usuario
        $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $imagen);

        if ($resultado === "Exito") {
            // Registro exitoso
            echo "Registro exitoso. Bienvenido, $nick!";
        } else {
            // Manejar el error de registro
            echo "Error: " . $resultado;
        }
    }

    public function compruebaLogin(){
        $usuarioDB=new Usuario();
        $correoValido=Validators::evitarInyeccion($_POST["correo"]);
        $passwordValida=Validators::evitarInyeccion($_POST["password"]);

        $usuario=$usuarioDB->logIn($correoValido, $passwordValida);

        if(!is_bool($usuario)){
            $_SESSION["usuarioActivo"]=$usuario["id"];
            $_SESSION["Nick"]=$usuario["Nick"];
            if($usuario["Admin"]==1){
                $_SESSION["Admin"]=true;
            }else{
                $_SESSION["Admin"]=false;
            }
            

            echo json_encode(["Success"=>"Todo Correcto."]);
        }else{
            echo json_encode(["Error"=>"Datos incorrectos."]);
        }
    }

    public function logout(){
        Security::closeSession();
    }
}