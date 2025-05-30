<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Lista;
use App\Models\Plataforma;
use App\Models\Genero;
use App\Models\Review;
use App\Models\Usuario;

use App\Core\Validators;
use App\Core\Security;
use App\Models\Venta;

use DateTime;
use PDO;
use PDOStatement;

class ControllerAJAX {

    public function botones_juego(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $juegoDB=new Juego();
        $listaDB=new Lista();

        $id_juego=Validators::evitarInyeccion($_POST["id_juego"]);

        $id_usuario = $_SESSION['usuarioActivo']; // Obtener el ID del usuario desde la sesión.

        $listas_usuario = $listaDB->getListasUsuario($id_usuario); // Obtener las listas del usuario.  

        $listas_juego = $listaDB->compruebaJuegoLista($id_juego, $listas_usuario); // Comprobar si el juego está en las listas del usuario.

        $wishlist = false;
        $backlog = false;
        $completed = false;
        $playing = false;

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

        $estados=[$wishlist, $backlog, $completed, $playing];

        echo json_encode(["estados"=> $estados]);
    }

    public function lista_juegos(){
        
        $juegoDB=new Juego();
        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $filtros=json_decode($_POST["filtros"], true);

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

        if(!empty($_SESSION)){
             $id_usuario = $_SESSION['usuarioActivo']; // Obtener el ID del usuario desde la sesión.
            $listas_usuario = $listaDB->getListasUsuario($id_usuario); // Obtener las listas del usuario.

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

    public function lista_reviews(){
        $reviewDB=new Review();
        $usuarioDB=new Usuario();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_juego = $_POST["id_juego"];
        $sesion_activa = $_SESSION["usuarioActivo"] ?? null; // Obtener el ID del usuario desde la sesión.
        $admin = $_SESSION["Admin"] ?? null; // Comprobar si el usuario es admin.
        // $filtros=json_decode($_POST["filtros"], true);


        $total_reviews = $reviewDB->countAllReviewsJuego($id_juego);

        $total_paginas = ceil($total_reviews / $limite);

        $reviews= $reviewDB->getAllReviewsJuego($id_juego, (int)$inicio, (int)$limite);

        foreach($reviews as &$review){
            $usuario = $usuarioDB->getbyId($review["id_Escritor"]);
            $review["Nick_Usuario"] = $usuario["Nick"]; // Añadir el Nick del usuario a la review.
            $review["Imagen_Usuario"] = $usuario["Imagen_usuario"]; // Añadir la imagen del usuario a la review.

            if(($sesion_activa != null && $admin != null) && ($admin==true)){ // Comprobar si el usuario es el autor de la review o si es admin.
                $review["editable"] = true; // Añadir un campo editable a la review.
            }else{
                $review["editable"] = false; // Añadir un campo editable a la review.
            }

            if($sesion_activa != null && $review["id_Escritor"] == $sesion_activa){ // Comprobar si el usuario es el autor de la review o si es admin.
                $review["editable"] = true; // Añadir un campo editable a la review.
            }else{
                $review["editable"] = false; // Añadir un campo editable a la review.
            }

            if(strlen($review["Contenido"]) > 10){ 
                $contenido_reducido = str_split($review['Contenido'], 10)[0];
                $contenido_reducido .= " ...";
                $review["contenidoReducido"] = $contenido_reducido; // Reducir el contenido de la review.
            }
        }

        echo json_encode(["reviews"=>$reviews, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);  
    }

    public function lista_compras_perfil(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $ventaDB=new Venta();
        $plataformaDB=new Plataforma();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];


        $total_compras = $ventaDB->getCountComprasUsuario($id_usuario);
        

        $total_paginas = ceil($total_compras / $limite);
        
        $compras = $ventaDB->getListComprasUsuario((int)$id_usuario, (int)$inicio, (int)$limite); 

        foreach ($compras as &$compra) {
            $compraID=$ventaDB->getById($compra["id_Post"]);

            $compra["Producto"]=["id"=>$compraID["id"], "Titulo"=>$compraID["Titulo"], "Imagen"=>$compraID["img_venta"]];
        }

        echo json_encode(["compras"=>$compras, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function lista_ventas_perfil(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $ventaDB=new Venta();
        $plataformaDB=new Plataforma();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];


        $total_ventas = $ventaDB->getCountProductosUsuario($id_usuario);
        

        $total_paginas = ceil($total_ventas / $limite);
        
        $ventas = $ventaDB->getListProductosUsuario((int)$id_usuario, (int)$inicio, (int)$limite); 

        foreach ($ventas as &$venta) {
            $consola=$plataformaDB->getById($venta["Consola"]);

            $venta["Consola"]=$consola["Nombre"];
        }

        echo json_encode(["ventas"=>$ventas, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function registrarProducto(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

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

    public function add_review(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $id_juego = $_POST["id_juego"];
        $review = $_POST["review"];
        $id_usuario = $_SESSION["usuarioActivo"]; // Obtener el ID del usuario desde la sesión.

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

        if(empty($_SESSION)){
            Security::closeSession();
        }

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

        if(empty($_SESSION)){
            Security::closeSession();
        }

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
        $imagen = $_FILES['imagen_perfil'] ?? null; // Obtener la imagen si se proporciona

        if ($imagen != null) {
            $Nombreimagen=uniqid();

            $extension = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['jpg', 'jpeg', 'png'];

            if ($imagen['size'] > (12 * 1024 * 1204)) { //Que el tamaño no sea mayor de 12 mb

                return "Imagen demasiado pesada";
            } elseif (!in_array($extension, $extensionesPermitidas)) {

                return "El archivo tiene un tipo no permitido";
            } else {

                $filename = $Nombreimagen . ".jpg";
                $tempName = $imagen['tmp_name'];
                if (isset($filename)) {
                    if (!empty('$filename')) {
                        $location = __DIR__. "/../../public/IMG/Users-img/" . $filename;
                        move_uploaded_file($tempName, $location);
                    }
                }
            }
        }

        

        if(!empty($imagen)){
            // Lógica para registrar al usuario
            $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $direccion, $filename);
        }else{
            $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $direccion);
        }

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

            // Datos de Sesion para comprobar el tiempo máximo de la Sesión.
            $_SESSION['admin_session_started'] = time();

            // Comprobacion Admin
            if($usuario["Admin"]==1){
                $_SESSION["Admin"]=true;
            }else{
                $_SESSION["Admin"]=false;
            }

            // Comprobacion Premium
            if($usuario["Premium"]==1){
                $_SESSION["Premium"]=true;
            }else{
                $_SESSION["Premium"]=false;
            }
            echo json_encode(["result"=>"ok", "ultimo_lugar"=>$_COOKIE["ultimoLugar"]]);
            setcookie("ultimoLugar", "", time()-1, "/"); // Guardar la cookie del ultimo lugar visitado.

        }else{
            echo json_encode(["result"=>"error", "error"=>"Datos incorrectos"]);
        }
    }

    public function logout(){
        Security::closeSession();
    }

    public function eliminarDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        if($_SESSION["Admin"]!=true){
            Security::closeSession();
        }

        $id=$_POST["id"];
        $entidad=$_POST["entidad"];

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $rutaImagen= __DIR__. "/../../public/IMG/Users-img/".$usuarioDB->getById($id)["Imagen_usuario"];
                $usuarioDB->delete($id);
                $usuarioDB->eliminarImagen($rutaImagen);
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

    public function eliminarReview(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $reviewDB=new Review();

        $id=Validators::evitarInyeccion($_POST["id"]);

        $reviewDB->delete($id);
    }

   public function datosModificarDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

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

    public function modificarDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $entidad=Validators::evitarInyeccion($_POST["entidad"]);
        $datosPost=json_decode($_POST["datos"], true);

        $datos=[];

        foreach($datosPost as $key => $dato){
            $datos[$key]=Validators::evitarInyeccion($dato);
        }


        $id=$datos["id"];

        // Declaro PDOStatement aqui para que el ID reconozca que es lo que devuelve update() siempre es un PDOStatement
        $item=new PDOStatement();

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
                if($datos["img_venta"]==""){
                    $datos["img_venta"]="default-game.jpg"; // Imagen por defecto si no se proporciona una imagen.
                }
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

        if($item->rowCount()==1){
            echo json_encode(["result"=>"ok"]);
        }else{
            echo json_encode(["result"=>"fail"]);
        }
    }

    public function addDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

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
    
    public function gestionarCompra(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $ventaDB=new Venta();
        $usuarioDB=new Usuario();

        $id_producto=Validators::evitarInyeccion($_POST["id_producto"]);
        $id_usuario=$_SESSION["usuarioActivo"];
        $fecha_compra=date("Y-m-d H:i:s");

        if($id_producto==-1){
            $premium=$usuarioDB->conseguirPremium($id_usuario);
        }else{
            $baja_stock=$ventaDB->bajarStock($id_producto);
        }

        
        $agregar_vendido=$ventaDB->agregarVendido($id_producto, $id_usuario, $fecha_compra);

        if( ($baja_stock || $premium) && $agregar_vendido){
            echo json_encode(["result"=> "Producto Vendido con exito."]);
        }else{
            echo json_encode(["error"=>"Error de Base de datos."]);
        }
    }

    public function listaAdmin(){

        if(empty($_SESSION)){
            Security::closeSession();
        }
        
        $pagina = Validators::evitarInyeccion($_POST["pagina"]);
        $limite = Validators::evitarInyeccion($_POST["limite"]);
        $inicio = Validators::evitarInyeccion($_POST["inicio"]);
        $entidad=Validators::evitarInyeccion($_POST["entidad"]);
        if(isset($_POST["busqueda"])){
            $busqueda=Validators::evitarInyeccion($_POST["busqueda"]);
        }
        

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
                $entidadDB=new Venta();
                break;
            default:
                echo "Error de Entidad";
                break;
        }


        if(isset($busqueda) && !empty($busqueda)){
            $busqueda = "%{$busqueda}%"; // Preparar la búsqueda para LIKE
            $total_datos = $entidadDB->buscarAdminCount($busqueda);
            $datos = $entidadDB->buscarAdmin($busqueda, (int)$inicio, (int)$limite);
            $columnasDB = $entidadDB->listaColumnas();
        }else{
            if($entidad == "post_vendidos"){
                $total_datos=$entidadDB->cuentaVentas();
                $datos=$entidadDB->muestraAllVentasLimit((int)$inicio, (int)$limite);
                $columnasDB = $entidadDB->muestraColumnasVentas();
            }else{
                $total_datos = $entidadDB->getCount();
                $datos = $entidadDB->getAllLimit((int)$inicio, (int)$limite);
                $columnasDB = $entidadDB->listaColumnas();
            }   
        }

        $total_paginas = ceil($total_datos / $limite);


        
        $columnas = [];
        foreach ($columnasDB as $columnaDB) {
            array_push($columnas, $columnaDB["Field"]);
        }

        echo json_encode(["columnas"=>$columnas ,"datos"=>$datos, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function lista_whislist(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];

        $total_wish = $listaDB->getCountListasUsuario($id_usuario, "wishlist");

        $total_paginas = ceil($total_wish / $limite);

        $whislist = $listaDB->getUserLists($id_usuario, "wishlist", (int)$inicio, (int)$limite); 

        echo json_encode(["juegos"=>$whislist, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function lista_playing(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];

        $total_play = $listaDB->getCountListasUsuario($id_usuario, "playing");

        $total_paginas = ceil($total_play / $limite);

        $playing = $listaDB->getUserLists((int)$id_usuario, "playing", (int)$inicio, (int)$limite); 

        echo json_encode(["juegos"=>$playing, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function lista_completed(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];

        $total_comp = $listaDB->getCountListasUsuario($id_usuario, "completed");

        $total_paginas = ceil($total_comp / $limite);

        $completed = $listaDB->getUserLists((int)$id_usuario, "completed", (int)$inicio, (int)$limite); 

        echo json_encode(["juegos"=>$completed, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function lista_backlog(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $listaDB=new Lista();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $id_usuario = $_SESSION["usuarioActivo"];

        $total_back = $listaDB->getCountListasUsuario($id_usuario, "backlog");

        $total_paginas = ceil($total_back / $limite);

        $backlog = $listaDB->getUserLists((int)$id_usuario, "backlog", (int)$inicio, (int)$limite); 

        echo json_encode(["juegos"=>$backlog, "pagina"=>$pagina, "total_paginas"=>$total_paginas]);
    }

    public function vaciarProducto(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $ventaDB = new Venta();
        
        $datos =  json_decode(file_get_contents('php://input'), true);

        $id_producto = Validators::evitarInyeccion($datos["id_producto"]);
        if (empty($id_producto)) {
            echo json_encode(["result" => "error", "mensaje" => "ID del producto no proporcionado."]);
            exit;
        }

        if ($ventaDB->vaciarProducto($id_producto)) {
            echo json_encode(["result" => "ok", "mensaje" => "Producto vaciado correctamente."]);
        } else {
            echo json_encode(["result" => "error", "mensaje" => "Error al vaciar el producto."]);
        }
    }

    public function AJAXPaypal(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $clientId = $_ENV['PAYPAL_CLIENT_ID'];
        $clientSecret = $_ENV['PAYPAL_CLIENT_SECRET'];
        $body = json_decode(file_get_contents('php://input'), true); //php://input permite leer el cuerpo de la solicitud POST cuando es un JSON.
        $productoId = $body['productoId'] ?? null;

        if($productoId==-1){

        }else if($productoId != $_SESSION["id_venta"]){
            echo json_encode(["error" => "La Id del producto ha sido modificada"]);
            exit;
        }

        $ventaBD=new Venta();

        $precio_producto = $ventaBD->getById($productoId)["Precio"];

        $precio=$precio_producto + 2.99;

        if($_SESSION["Premium"]==true || $productoId==-1){
            $precio=$precio_producto;
        }

        // SANDBOX: Obtener token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Accept-Language: en_US" //Se obtiene el token en inglés para evitar problemas de codificación. 
        ]);
        $tokenResponse = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($tokenResponse['access_token'])) {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo obtener el token de PayPal']);
            exit;
        }

        $accessToken = $tokenResponse['access_token'];

        // Crear orden en SANDBOX
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => $precio
                ]
            ]]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        $orderResponse = json_decode(curl_exec($ch), true);
        curl_close($ch);

        // Devuelve el orderID al frontend
        if (isset($orderResponse['id'])) {
            echo json_encode(['orderID' => $orderResponse['id']]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo crear la orden']);
        }
    }

    public function cambiarPass(){

        if (empty($_SESSION)) {
            Security::closeSession();
        }

        $usuarioDB = new Usuario();

        $id_usuario = Validators::evitarInyeccion($_POST["id_usuario"]);
        $pass = Validators::evitarInyeccion($_POST["Pass"]);

        $result = $usuarioDB->cambiarPass($id_usuario, $pass);

        if ($result == 1) {
            echo json_encode(["result" => "ok"]);
        } else {
            echo json_encode(["result" => "fail", "Columnas afectadas" => $result]);
        }
    }

    public function compruebaPass(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $usuarioDB=new Usuario();

        $passActual=Validators::evitarInyeccion($_POST["passActual"]);
        $id_usuario=$_SESSION["usuarioActivo"];

        $usuario=$usuarioDB->getById($id_usuario);

        if(password_verify($passActual, $usuario["Password"])){
            echo json_encode(["result"=>"ok"]);
        }else{
            echo json_encode(["result"=>"fail", "mensaje"=>"Contraseña incorrecta"]);
        }
    }

    public function getGenPlatJuegoAdmin(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        if(!$_SESSION["Admin"]){
            Security::closeSession();
        }

        $generoDB=new Genero();
        $plataformaDB=new Plataforma();

        $id_juego = Validators::evitarInyeccion($_POST["id_juego"]);

        $generos = $generoDB->getGenerosIDJuegoById($id_juego);
        $plataformas = $plataformaDB->getPlataformasIDJuegoById($id_juego);

        echo json_encode(["generos"=>$generos, "plataformas"=>$plataformas]);
    }
}