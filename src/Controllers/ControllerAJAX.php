<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Plataforma;
use App\Models\Review;
use App\Models\Usuario;

use App\Core\Validators;
use App\Core\Security;
use App\Models\Venta;

use DateTime;

class ControllerAJAX {

    public function lista_juegos(){
        
        $juegoDB=new Juego();
        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $filtros=json_decode($_POST["filtros"], true);

        $id_usuario = $_SESSION['usuarioActivo']; // Obtener el ID del usuario desde la sesión.
        $listas_usuario = $listaDB->getListasUsuario($id_usuario); // Obtener las listas del usuario.

        if(empty($filtros)){
            $total_juegos = $juegoDB->getCount();
        }else{
            if(!empty($filtros["fechaSalida"])){
                $fechaFin = new DateTime($filtros["fechaSalida"]);
                $fechaFin->modify('+1 year');

                $fechaFinStr = $fechaFin->format('Y-m-d');

                $filtros["fechaNextMonth"]=$fechaFinStr;
            }

            $total_juegos = $juegoDB->getCountFiltros($filtros);
        }
        

        $total_paginas = ceil($total_juegos / $limite);
        
        $juegos = $juegoDB->getListGames((int)$inicio, (int)$limite, $filtros); // Obtener 10 juegos

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

        if(!empty($_SESSION)){
            echo json_encode(["filtros"=>$filtros, "juegos"=>$juegos, "pagina"=>$pagina, "total_paginas"=>$total_paginas, "sesion"=>$_SESSION["usuarioActivo"]]);
        }else{
            echo json_encode(["filtros"=>$filtros, "juegos"=>$juegos, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
        }
        
    }

    public function lista_ventas(){
        
        $ventaDB=new Venta();
        $plataformaDB=new Plataforma();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $filtros=json_decode($_POST["filtros"], true);

        if(empty($filtros)){
            $total_ventas = $ventaDB->getCount();
        }else{
            if(!empty($filtros["fechaSalida"])){
                $fechaFin = new DateTime($filtros["fechaSalida"]);
                $fechaFin->modify('+1 year');

                $fechaFinStr = $fechaFin->format('Y-m-d');

                $filtros["fechaNextMonth"]=$fechaFinStr;
            }

            $total_ventas = $ventaDB->getCountFiltros($filtros);
        }

        $total_paginas = ceil($total_ventas / $limite);
        
        $ventas = $ventaDB->getListSells((int)$inicio, (int)$limite, $filtros); // Obtener 10 juegos

        foreach ($ventas as &$venta) {
            $consola=$plataformaDB->getById($venta["Consola"]);

            $venta["Consola"]=$consola["Nombre"];
        }

        echo json_encode(["filtros"=>$filtros ,"ventas"=>$ventas, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function registrarProducto(){
        $ventaDB=new Venta();

        $datos = json_decode($_POST["datos"], true);


        // Validar que los datos no estén vacíos y evitar inyecciones.
        $titulo=Validators::evitarInyeccion($datos["Titulo"]);
        $estado=Validators::evitarInyeccion($datos["Estado"]);
        $consola=Validators::evitarInyeccion($datos["Consola"]);
        $precio=Validators::evitarInyeccion($datos["Precio"]);
        $estado_venta=Validators::evitarInyeccion($datos["Estado_Venta"]);

        if($estado_venta === "Sin Stock"){
            $stock = 0; // Si el estado de venta es "Sin Stock", establecer stock a 0.
        } else {
            $stock = Validators::evitarInyeccion($datos["Stock"]); // Si no, obtener el stock proporcionado.
        }

        $imagen=$_FILES['imagen']['name'] ?? null; // Obtener la imagen si se proporciona
        $id_vendedor=$_SESSION["usuarioActivo"]; // Obtener el ID del usuario desde la sesión.
        $id_juego = Validators::evitarInyeccion($datos["id_juego"]); // Obtener el ID del juego si se proporciona

        if(!empty($_FILES['imagen']['name'])) {
            $ruta_imagen = __DIR__ . "/../../public/img/productos/" . basename($imagen);
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
                echo json_encode(["error"=>"Error al subir la imagen."]);
                exit;
            }
        } else {
            $imagen = 'default-game.jpg'; // Si no se proporciona imagen, establecer como null.
        }
        
        if($estado_venta !== "Sin Stock"){
            if(empty($titulo) || empty($estado) || empty($consola) || empty($precio) || empty($estado_venta) || empty($stock) || empty($id_vendedor)){
                echo json_encode(["error"=>"Error: Datos incompletos."]);
                exit;
            }
        }else{
            if(empty($titulo) || empty($estado) || empty($consola) || empty($precio) || empty($estado_venta) || empty($id_vendedor)){
            echo json_encode(["error"=>"Error: Datos incompletos."]);
            exit;
        }
        }
        
        

        if($ventaDB->create(array(
            "Titulo" => $titulo,
            "Estado" => $estado,
            "Consola" => $consola,
            "Precio" => $precio,
            "Estado_Venta" => $estado_venta,
            "Stock" => $stock,
            "img_venta" => $imagen,
            "id_Vendedor" => $id_vendedor,
            "id_juego" => $id_juego
        ))){
            echo json_encode(["result"=>"Producto registrado con exito."]);
        }else{
            echo json_encode(["error"=>"Error al registrar el producto."]);
        }
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
        $id_usuario = $_SESSION['usuarioActivo'] ?? null; // Obtener el ID del usuario desde la sesión.

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

                echo json_encode(["error" =>"No se ha podido añadir el juego a la lista."]);
            }
        } else {
            echo json_encode(["error" =>"Datos incompletos."]);
        }
    }

    public function eliminarJuegoLista(){

        $id_juego = $_POST['id_juego'] ?? null;
        $lista = $_POST['lista'] ?? null;
        $id_usuario = $_SESSION['usuarioActivo'] ?? null; // Obtener el ID del usuario desde la sesión.

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
        $listaDB=new Lista();
        
        $nombre = Validators::evitarInyeccion($_POST['nombre']);
        $apellido = Validators::evitarInyeccion($_POST['apellido']);
        $correo = Validators::evitarInyeccion($_POST['email']);
        $pass = Validators::evitarInyeccion($_POST['password']);
        $nick = Validators::evitarInyeccion($_POST['nick']);
        $direccion = Validators::evitarInyeccion($_POST['direccion']);
        // $imagen = $_FILES['imagen_perfil']['name'] ?? null; // Obtener la imagen si se proporciona

        // Lógica para registrar al usuario
        $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $direccion);

        if ($resultado === "Correo") {
            // Manejar el error de registro
            echo json_encode(["result"=>"error", "Error" => "correo"]);
        } else {
            // Registro exitoso
            $listaDB->creaListasBasicas($nick, $resultado);
            echo json_encode(["result" =>"ok"]);
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

    public function eliminarDato(){
        $id=$_POST["id"];
        $entidad=$_POST["entidad"];

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $usuarioDB->delete($id);
                echo "Todo Correcto";
                break;
            case "juegos":
                $juegosDB=new Juego();
                $juegosDB->delete($id);
                echo "Todo Correcto";
                break;
            case "reviews":
                $reviewsDB=new Review();
                $reviewsDB->delete($id);
                echo "Todo Correcto";
                break;
            case "productos":
                $ventaDB=new Venta();
                $ventaDB->delete($id);
                echo "Todo Correcto";
                break;
            case "post_vendidos":
                // $usuarioDB=new Venta(); // Cambiar a clase Vendido.
                // $usuarioDB->delete($id);
                // echo "Todo Correcto";
                echo "Error de Entidad, entidad equivocada";
                break;
            default:
                echo "Error de Entidad";
                break;
        }
    }

    public function modificarDato(){

        $entidad=$_POST["entidad"];
        $datos=json_decode($_POST["datos"], true);

        $id=$datos["id"];

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $item=$usuarioDB->update($datos, $id);
                break;
            case "juegos":
                $juegosDB=new Juego();
                $item=$juegosDB->update($datos, $id);
                break;
            case "reviews":
                $reviewsDB=new Review();
                $item=$reviewsDB->update($datos, $id);
                break;
            case "productos":
                $ventaDB=new Venta();
                $item=$ventaDB->update($datos, $id);
                break;
            case "post_vendidos":
                // $usuarioDB=new Venta(); // Cambiar a clase Vendido.
                // $usuarioDB->delete($id);
                // echo "Todo Correcto";
                echo "Error de Entidad, entidad equivocada";
                break;
            default:
                echo "Error de Entidad";
                break;
        }
    }

    public function addDato(){
        $entidad=$_POST["entidad"];
        $datos=json_decode($_POST["datos"], true);

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $listaDB=new Lista();
                $datos["Password"]=Security::encryptPass($datos["Password"]);
                
                if($datos["Imagen_usuario"]==""){
                    $datos["Imagen_usuario"]="default-user.png"; // Imagen por defecto si no se proporciona una imagen.
                }
                
                $item=$usuarioDB->create($datos);
                $listaDB->creaListasBasicas($datos, $item); // Crear listas basicas del usuario.
                break;
            case "juegos":
                $juegosDB=new Juego();
                $item=$juegosDB->create($datos);
                break;
            case "reviews":
                $reviewsDB=new Review();
                $item=$reviewsDB->create($datos);
                break;
            case "productos":
                $ventaDB=new Venta();
                $item=$ventaDB->create($datos);
                break;
            case "post_vendidos":
                // $usuarioDB=new Venta(); // Cambiar a clase Vendido.
                // $usuarioDB->delete($id);
                // echo "Todo Correcto";
                echo "Error de Entidad, entidad equivocada";
                break;
            default:
                echo "Error de Entidad";
                break;
        }
    }

    public function datosModificarDato(){
        $id=$_POST["id"];
        $entidad=$_POST["entidad"];

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $item=$usuarioDB->getById($id);
                echo json_encode(["dato" => $item, "id"=>$id]);
                break;
            case "juegos":
                $juegosDB=new Juego();
                $item=$juegosDB->getById($id);
                echo json_encode(["dato" => $item]);
                break;
            case "reviews":
                $reviewsDB=new Review();
                $item=$reviewsDB->getById($id);
                echo json_encode(["dato" => $item]);
                break;
            case "productos":
                $ventaDB=new Venta();
                $item=$ventaDB->getById($id);
                echo json_encode(["dato" => $item]);
                break;
            case "post_vendidos":
                // $usuarioDB=new Venta(); // Cambiar a clase Vendido.
                // $usuarioDB->delete($id);
                // echo "Todo Correcto";
                echo "Error de Entidad, entidad equivocada";
                break;
            default:
                echo "Error de Entidad";
                break;
        }
    }
    
    public function gestionarCompra(){
        $ventaDB=new Venta();

        $id_producto=Validators::evitarInyeccion($_POST["id_producto"]);
        $id_usuario=$_SESSION["usuarioActivo"];
        $fecha_compra=date("Y-m-d H:i:s");

        $baja_stock=$ventaDB->bajarStock($id_producto);
        $agregar_vendido=$ventaDB->agregarVendido($id_producto, $id_usuario, $fecha_compra);

        if($baja_stock && $agregar_vendido){
            echo json_encode(["result"=> "Producto Vendido con exito."]);
        }else{
            echo json_encode(["error"=>"Error de Base de datos."]);
        }
    }

    public function listaAdmin(){
        
        $pagina = Validators::evitarInyeccion($_POST["pagina"]);
        $limite = Validators::evitarInyeccion($_POST["limite"]);
        $inicio = Validators::evitarInyeccion($_POST["inicio"]);
        $entidad=Validators::evitarInyeccion($_POST["entidad"]);

        switch ($entidad) {
            case "usuarios":
                $entidadDB=new Usuario();
                break;
            case "juegos":
                $entidadDB=new Juego();
                break;
            case "reviews":
                $entidadDB=new Review();
                break;
            case "productos":
                $entidadDB=new Venta();
                break;
            case "post_vendidos":
                // $usuarioDB=new Venta(); // Cambiar a clase Vendido.
                // $usuarioDB->delete($id);
                // echo "Todo Correcto";
                echo "Error de Entidad, entidad equivocada";
                break;
            default:
                echo "Error de Entidad";
                break;
        }


        $total_datos = $entidadDB->getCount();

        $total_paginas = ceil($total_datos / $limite);
        
        $datos = $entidadDB->getAllLimit((int)$inicio, (int)$limite); // Obtener 10 juegos

        $columnasDB = $entidadDB->listaColumnas();
        $columnas = [];
        foreach ($columnasDB as $columnaDB) {
            array_push($columnas, $columnaDB["Field"]);
        }

        echo json_encode(["columnas"=>$columnas ,"datos"=>$datos, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }
}