<?php

namespace App\Controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
use Dotenv\Validator;
use PDO;
use PDOStatement;

class ControllerAJAX {

    // Juegos

    /**
     * Devuelve el estado de un juego en relación con las listas del usuario autenticado.
     *
     * Verifica si el juego está incluido en alguna de las listas del usuario (wishlist, backlog,
     * completado o en curso) y devuelve esta información como un array JSON codificado.
     *
     * @return void Devuelve un JSON con la clave 'estados', que contiene un array de booleanos indicando:
     *              - [0] => wishlist
     *              - [1] => backlog
     *              - [2] => completed
     *              - [3] => playing
     */
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

    /**
     * Devuelve una lista de juegos paginada con sus estados de lista para el usuario, si ha iniciado sesión.
     *
     * Aplica filtros opcionales recibidos por POST (como fecha de salida), calcula el número total de páginas,
     * y devuelve los juegos para la página solicitada. También marca cada juego con su estado en las listas
     * del usuario autenticado (si existe sesión).
     *
     * @return void Devuelve un JSON que incluye:
     *              - 'filtros' => filtros aplicados
     *              - 'juegos' => array de juegos con sus estados en listas
     *              - 'pagina' => número de la página actual
     *              - 'total_paginas' => total de páginas disponibles
     *              - 'sesion' => ID del usuario (si hay sesión activa)
     */
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

    /**
     * Añade un juego a una de las listas del usuario autenticado (wishlist, backlog, completed, playing).
     *
     * Este método se espera que sea llamado vía AJAX desde el frontend. Los datos necesarios
     * son enviados por POST. Valida que exista sesión y los datos requeridos antes de intentar
     * agregar el juego a la lista correspondiente.
     *
     * Parámetros esperados por POST:
     * - 'id_juego' => ID del juego a agregar
     * - 'lista'    => Código corto de la lista ('wish', 'back', 'comp', 'play')
     *
     * Requiere que el usuario esté autenticado y que exista $_SESSION['usuarioActivo'].
     *
     * @return void Devuelve un JSON con:
     *              - 'result' => mensaje de éxito si se agrega correctamente
     *              - 'error'  => mensaje de error en caso de fallo o datos incompletos
     */
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

                echo json_encode(["result" =>"Game added to list {$nombre_lista} correctly."]);
            } else {

                echo json_encode(["error" =>"The game could not be added to the list."]);
            }
        } else {
            echo json_encode(["error" =>"Incomplete data."]);
        }
    }

    /**
     * Elimina un juego de una de las listas del usuario autenticado (wishlist, backlog, completed, playing).
     *
     * Este método es invocado típicamente mediante una petición AJAX. Valida que el usuario esté
     * autenticado mediante la sesión y que se reciban los parámetros necesarios por POST.
     *
     * Parámetros esperados por POST:
     * - 'id_juego' => ID del juego que se desea eliminar
     * - 'lista'    => Código corto de la lista desde la que se elimina ('wish', 'back', 'comp', 'play')
     *
     * Requiere que exista una sesión activa con el identificador del usuario (`$_SESSION['usuarioActivo']`).
     *
     * @return void Devuelve un JSON con:
     *              - 'result' => mensaje de éxito si se elimina correctamente
     *                         => mensaje de error si fallan los datos o la eliminación
     */
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

                echo json_encode(["result" =>"Game removed from the list {$nombre_lista} correctly."]);
            } else {

                echo json_encode(["result" =>"Error: The game could not be removed from the list."]);
            }
        } else {
            echo json_encode(["result" =>"Error: Incomplete data."]);
        }
    }

    // Ventas

    /**
     * Obtiene y devuelve una lista paginada de ventas filtradas, con información de la consola.
     *
     * Recibe por POST:
     *  - "pagina": número de página actual
     *  - "limite": cantidad de resultados por página
     *  - "inicio": índice inicial para la consulta
     *  - "filtros": JSON con filtros aplicables (p.ej. fechaSalida)
     *
     * Si se incluye el filtro "fechaSalida", se añade un filtro adicional de un año después de esa fecha.
     * Luego se calcula el total de páginas, y se obtienen las ventas para la página solicitada.
     * Cada venta incluye el nombre de la consola, obtenido desde la tabla de plataformas.
     *
     * Devuelve un JSON con:
     *  - "filtros": filtros aplicados
     *  - "ventas": array de ventas con datos y nombre de consola
     *  - "pagina": página actual
     *  - "total_paginas": total de páginas calculado
     *
     * @return void Imprime JSON con la información descrita.
     */
    public function lista_ventas(){
        
        $ventaDB=new Venta();
        $plataformaDB=new Plataforma();

        $pagina = $_POST["pagina"];
        $limite = $_POST["limite"];
        $inicio = $_POST["inicio"];
        $filtros=json_decode($_POST["filtros"], true);

        if(empty($filtros)){
            $total_ventas = $ventaDB->getCountListaVentas();
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

    /**
     * Registra un nuevo producto en la base de datos asociado a una venta.
     *
     * Requiere sesión activa.
     *
     * Recibe por POST un JSON en "datos" con los siguientes campos:
     *  - "Titulo": título del producto
     *  - "Estado": estado del producto
     *  - "Consola": ID o identificador de la consola
     *  - "Precio": precio del producto
     *  - "Estado_Venta": estado de la venta (p.ej. "Disponible", "Sin Stock")
     *  - "Stock": cantidad en stock (opcional si "Estado_Venta" es "Sin Stock")
     *  - "id_juego": ID del juego asociado (opcional)
     *
     * También puede recibir un archivo de imagen en 'img'.
     *
     * Se valida que los datos no estén vacíos y se previenen inyecciones.
     * Si hay imagen, se guarda en el servidor y se usa el nombre generado.
     * Finalmente se crea el registro en la base de datos.
     *
     * Responde con JSON indicando:
     *  - "result": "ok" si éxito, "fail" o "error" en caso contrario
     *  - "mensaje": mensaje descriptivo en caso de fallo
     *
     * @return void Imprime JSON con resultado de la operación.
     */
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

        $imagen=$_FILES['img'] ?? null; // Obtener la imagen si se proporciona
        $id_vendedor=$_SESSION["usuarioActivo"]; // Obtener el ID del usuario desde la sesión.
        $id_juego = Validators::evitarInyeccion($datos["id_juego"]); // Obtener el ID del juego si se proporciona

        //En caso de que haya una imagen se sube al servidor y se devuelve una id aleatoria como nombre.
        if(!empty($_FILES['img']['name'])) {
            $Nombreimagen=$ventaDB->addImagen($imagen);

        } else {
            $Nombreimagen = 'default-game'; // Si no se proporciona imagen, establecer como null.
        }
        
        if($estado_venta !== "Sin Stock"){
            if(empty($titulo) || empty($estado) || empty($consola) || empty($precio) || empty($estado_venta) || empty($stock) || empty($id_vendedor)){
                echo json_encode(["error"=>"Error: Incomplete data."]);
                exit;
            }
        }else{
            if(empty($titulo) || empty($estado) || empty($consola) || empty($precio) || empty($estado_venta) || empty($id_vendedor)){
            echo json_encode(["error"=>"Error: Incomplete data."]);
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
            "img_venta" => $Nombreimagen.".jpg",
            "id_Vendedor" => $id_vendedor,
            "id_juego" => $id_juego
        ))){
            echo json_encode(["result"=>"ok"]);
        }else{
            echo json_encode(["result"=>"fail", "mensaje"=>"Error registering the product."]);
        }
    }

    /**
     * Gestiona la compra de un producto.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "id_producto": ID del producto a comprar.
     *
     * Si el producto es -1, se verifica si el usuario tiene Premium.
     * Si es otro producto, se intenta bajar el stock.
     * Luego se registra la venta para el usuario con la fecha actual.
     *
     * Responde con JSON indicando éxito o error.
     *
     * @return void Imprime JSON con resultado de la operación.
     */
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
            echo json_encode(["result"=> "Product Sold Successfully."]);
        }else{
            echo json_encode(["error"=>"Database Error."]);
        }
    }

    /**
     * Vacía (limpia) la información o stock de un producto.
     *
     * Requiere sesión activa.
     * Recibe un JSON en el cuerpo de la petición con:
     *  - "id_producto": ID del producto a vaciar.
     *
     * Valida que el ID esté presente, luego intenta vaciar el producto en la base de datos.
     *
     * Responde con JSON indicando si la operación fue exitosa o si hubo error.
     *
     * @return void Imprime JSON con resultado de la operación.
     */
    public function vaciarProducto(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $ventaDB = new Venta();
        
        $datos =  json_decode(file_get_contents('php://input'), true);

        $id_producto = Validators::evitarInyeccion($datos["id_producto"]);
        if (empty($id_producto)) {
            echo json_encode(["result" => "error", "mensaje" => "Product ID not provided."]);
            exit;
        }

        if ($ventaDB->vaciarProducto($id_producto)) {
            echo json_encode(["result" => "ok", "mensaje" => "Product emptied correctly."]);
        } else {
            echo json_encode(["result" => "error", "mensaje" => "Error emptying the product."]);
        }
    }

    /**
     * Gestiona la creación de una orden de pago con PayPal (Sandbox).
     *
     * Requiere sesión activa.
     * Lee JSON en el cuerpo de la petición con:
     *  - "productoId": ID del producto a comprar.
     *
     * Valida que el producto coincida con la sesión o sea -1 (caso especial).
     * Obtiene el precio del producto, aplicando recargo si el usuario no es Premium.
     * Solicita token de acceso a PayPal Sandbox.
     * Crea una orden de pago en PayPal Sandbox.
     *
     * Devuelve JSON con:
     *  - "orderID": ID de la orden si se creó correctamente.
     *  - "error": mensaje de error en caso de fallo.
     *
     * @return void Imprime JSON con resultado de la creación de la orden.
     */
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
            echo json_encode(["error" => "The product ID has been modified"]);
            exit;
        }

        $ventaBD=new Venta();

        $precio_producto = $ventaBD->getById($productoId)["Precio"];

        

        if($_SESSION["Premium"]==true || $productoId==-1){
            $precio=$precio_producto;
        }else{
            $precio=number_format($precio_producto + 2.99, 2, '.');
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
            echo json_encode(['error' => 'Could not get PayPal token']);
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
            echo json_encode(['error' => 'The order could not be created']);
        }
    }

    // Reviews

    /**
     * Obtiene y lista las reviews de un juego con paginación.
     *
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de reviews por página.
     *  - "inicio": índice inicial para consulta.
     *  - "id_juego": ID del juego cuyas reviews se consultan.
     *
     * Añade a cada review:
     *  - Nick e imagen del usuario que la escribió.
     *  - Campo "editable" indicando si la review puede ser editada por el usuario actual (autor o admin).
     *  - Campo "contenidoReducido" con una versión abreviada del contenido si es muy largo.
     *
     * Devuelve JSON con:
     *  - reviews: array con las reviews y campos adicionales.
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con las reviews paginadas.
     */
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

    /**
     * Añade una nueva review para un juego.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "id_juego": ID del juego a revisar.
     *  - "review": contenido de la review.
     *
     * Crea una nueva review asociada al usuario activo.
     *
     * Responde con JSON indicando éxito o error.
     *
     * @return void Imprime JSON con resultado de la operación.
     */
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
                echo json_encode(["result" => "Review successfully inserted"]);
            }
        } else {
            echo json_encode(["result" => "Error: Incomplete data."]);
        }
    }

    /**
     * Elimina una review dada su ID.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "id": ID de la review a eliminar.
     *
     * Realiza la eliminación en la base de datos.
     *
     * No devuelve respuesta explícita.
     *
     * @return void
     */
    public function eliminarReview(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $reviewDB=new Review();

        $id=Validators::evitarInyeccion($_POST["id"]);

        $reviewDB->delete($id);
    }

    // Perfil

    /**
     * Lista las compras realizadas por el usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de compras por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de compras del usuario activo y añade información básica
     * del producto comprado (id, título, imagen).
     *
     * Devuelve JSON con:
     *  - compras: array con las compras y datos del producto.
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con las compras paginadas del usuario.
     */
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

    /**
     * Lista los productos puestos a la venta por el usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de productos por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de productos a la venta y añade el nombre de la consola correspondiente.
     *
     * Devuelve JSON con:
     *  - ventas: array con productos a la venta y datos asociados.
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con los productos en venta del usuario.
     */
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

    /**
     * Lista los juegos en la wishlist del usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de juegos por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de juegos marcados en la wishlist del usuario.
     *
     * Devuelve JSON con:
     *  - juegos: array con los juegos en wishlist.
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con los juegos en wishlist del usuario.
     */
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

    /**
     * Lista los juegos en la lista "playing" del usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de juegos por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de juegos que el usuario está jugando actualmente.
     *
     * Devuelve JSON con:
     *  - juegos: array con los juegos en la lista "playing".
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con los juegos en la lista "playing" del usuario.
     */
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

    /**
     * Lista los juegos en la lista "completed" del usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de juegos por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de juegos que el usuario ha completado.
     *
     * Devuelve JSON con:
     *  - juegos: array con los juegos en la lista "completed".
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con los juegos en la lista "completed" del usuario.
     */
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

    /**
     * Lista los juegos en la lista "backlog" del usuario activo con paginación.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - "pagina": página actual para paginación.
     *  - "limite": cantidad de juegos por página.
     *  - "inicio": índice inicial para consulta.
     *
     * Obtiene la lista de juegos que el usuario tiene pendientes para jugar.
     *
     * Devuelve JSON con:
     *  - juegos: array con los juegos en la lista "backlog".
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * @return void Imprime JSON con los juegos en la lista "backlog" del usuario.
     */
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

    // Home

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * Recibe los datos del usuario por POST:
     *  - nombre
     *  - apellido
     *  - email
     *  - password
     *  - nick
     *  - direccion
     *  - imagen_perfil (archivo opcional)
     *
     * La imagen de perfil, si se proporciona, se procesa y guarda.
     * Valida y evita inyección en los datos recibidos.
     * Crea las listas básicas para el usuario registrado.
     *
     * Responde con JSON:
     *  - result: "ok" si el registro fue exitoso.
     *  - result: "error" y Error: "correo" si el correo ya está registrado.
     *
     * @return void Envía respuesta JSON con el resultado del registro.
     */
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
            $Nombreimagen=$usuario->addImagen($imagen);
        }

        if(!empty($imagen)){
            // Lógica para registrar al usuario
            $resultado = $usuario->register($nombre, $apellido, $correo, $pass, $nick, $direccion, $Nombreimagen.".jpg");
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

    /**
     * Comprueba las credenciales de login del usuario.
     *
     * Recibe por POST:
     *  - correo
     *  - password
     *
     * Valida los datos para evitar inyección.
     * Si el login es correcto, inicializa la sesión con datos del usuario,
     * incluyendo flags para admin y premium.
     * Envía JSON con resultado y último lugar visitado (cookie).
     * Si el login falla, responde con error.
     *
     * @return void Envía JSON con resultado "ok" o "error".
     */
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
            echo json_encode(["result"=>"error", "mensaje"=>"Incorrect data"]);
        }
    }

    /**
     * Cierra la sesión del usuario activo.
     *
     * Llama a Security::closeSession() para destruir la sesión.
     *
     * @return void No devuelve contenido.
     */
    public function logout(){
        Security::closeSession();
    }

    /**
     * Verifica si la contraseña actual proporcionada coincide con la almacenada.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - passActual: contraseña actual del usuario.
     *
     * Compara la contraseña con la almacenada en la base de datos (hash).
     * Responde con JSON:
     *  - result: "ok" si la contraseña es correcta.
     *  - result: "fail" con mensaje si es incorrecta.
     *
     * @return void Envía JSON con el resultado de la verificación.
     */
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
            echo json_encode(["result"=>"fail", "mensaje"=>"Incorrect password."]);
        }
    }

    // Admin

    /**
     * Elimina un dato de la base de datos según la entidad indicada.
     *
     * Requiere sesión activa y que el usuario sea admin.
     * Recibe por POST:
     *  - entidad: tipo de dato a eliminar (usuarios, juegos, reviews, productos, post_vendidos).
     *  - id: identificador del dato (excepto para post_vendidos, que usa producto, comprador y fecha).
     *
     * Para usuarios y productos elimina también la imagen asociada si no es la imagen por defecto.
     * Para usuarios elimina también las listas básicas asociadas.
     * Responde con un mensaje de texto "Todo Correcto" o "Error de Entidad".
     *
     * @return void Imprime mensaje de estado.
     */
    public function eliminarDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        if($_SESSION["Admin"]!=true){
            Security::closeSession();
        }

        
        
        $entidad=Validators::evitarInyeccion($_POST["entidad"]);

        if($entidad=="post_vendidos"){
            $producto=Validators::evitarInyeccion($_POST["producto"]);
            $comprador=Validators::evitarInyeccion($_POST["comprador"]);
            $fecha=Validators::evitarInyeccion($_POST["fecha"]);
        }else{
            $id=Validators::evitarInyeccion($_POST["id"]);
        }

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();

                $imagen=$usuarioDB->getById($id)["Imagen_usuario"];

                if($imagen != "default-user.jpg"){
                    $rutaImagen= __DIR__. "/../../public/IMG/Users-img/".$usuarioDB->getById($id)["Imagen_usuario"];
                    $usuarioDB->eliminarImagen($rutaImagen);
                }
                
                $listaDB=new Lista();
                $listaDB->eliminarListasBasicas($id);
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
                if($ventaDB->getById($id)["img_venta"] != "default-game.jpg"){
                    $rutaImagen= __DIR__. "/../../public/IMG/Productos-img/".$ventaDB->getById($id)["img_venta"];
                }
                $ventaDB->delete($id);
                if(isset($rutaImagen)){
                    $ventaDB->eliminarImagen($rutaImagen);
                }
                echo "Todo Correcto";
                break;
            case "post_vendidos":
                $ventaDB=new Venta(); 
                $ventaDB->deleteCompra($producto, $comprador, $fecha);
                echo "Todo Correcto";
                break;
            default:
                echo "Error de Entidad";
                break;
        }
    }

    /**
     * Obtiene los datos de una entidad para modificarla.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - entidad: tipo de dato (usuarios, juegos, reviews, productos, post_vendidos).
     *  - id (o producto, comprador y fecha para post_vendidos).
     *
     * Devuelve un JSON con los datos recuperados para la edición,
     * o un JSON con error si la entidad no existe.
     *
     * @return void Envía respuesta JSON con los datos o el error.
     */
   public function datosModificarDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        
        $entidad=$_POST["entidad"];

        if($entidad == "post_vendidos"){
            $id_Post=$_POST["producto"];
            $id_comprador=$_POST["comprador"];
            $fecha=$_POST["fecha"];
        }else{
            $id=$_POST["id"];
        }

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
                $ventaDB=new Venta();
                $item=$ventaDB->getCompra($id_Post, $id_comprador, $fecha);
                echo json_encode(["dato" => $item]);
                break;
            default:
            echo json_encode(["error" => "Entity Error"]);
                break;
        }
    }

    /**
     * Modifica un dato existente en la base de datos según la entidad indicada.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - entidad: tipo de dato a modificar (usuarios, juegos, reviews, productos, post_vendidos).
     *  - datos: JSON con los campos a actualizar (se desinfectan contra inyección).
     *  - img (opcional): archivo de imagen para actualizar la imagen asociada.
     *
     * Para usuarios y productos, si se proporciona una nueva imagen, elimina la antigua (si no es la por defecto)
     * y guarda la nueva.
     * Para post_vendidos actualiza usando un método específico.
     *
     * Responde con JSON indicando si la modificación fue exitosa o fallida.
     *
     * @return void Envía JSON con resultado "ok" o "fail".
     */
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

        if($entidad=="post_vendidos"){

        }else{
            $id=$datos["id"];
        }

        // Declaro PDOStatement aqui para que el ID reconozca que es lo que devuelve update() siempre es un PDOStatement
        $item=new PDOStatement();

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $imagen=$_FILES['img'] ?? null;

                if($imagen==null){
                    $datos["Imagen_usuario"]=$usuarioDB->getById($id)["Imagen_usuario"];
                    $item=$usuarioDB->update($datos, $id);
                }else{
                    // Eliminamos primero la imagen antigua. Si no es la imagen por defecto.
                    if($usuarioDB->getById($id)["Imagen_usuario"] != "default-game.jpg"){
                        $rutaImagenAntigua= __DIR__. "/../../public/IMG/Users-img/".$usuarioDB->getById($id)["Imagen_usuario"];
                        $usuarioDB->eliminarImagen($rutaImagenAntigua);
                    }
                    
                    // Agregamos la imagen nueva y guardamos el nombre de la imagen nueva en los datos.
                    $nombreImagen=$usuarioDB->addImagen($imagen);
                    $datos["Imagen_usuario"]=$nombreImagen . ".jpg";
                    $item=$usuarioDB->update($datos, $id);
                }
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

                $imagen=$_FILES['img'] ?? null;

                if($imagen==null){
                    $datos["img_venta"]=$ventaDB->getById($id)["img_venta"];
                    $item=$ventaDB->update($datos, $id);
                }else{
                    // Eliminamos primero la imagen antigua. Si no es la imagen por defecto.
                    if($ventaDB->getById($id)["img_venta"] != "default-game.jpg"){
                        $rutaImagenAntigua= __DIR__. "/../../public/IMG/Productos-img/".$ventaDB->getById($id)["img_venta"];
                        $ventaDB->eliminarImagen($rutaImagenAntigua);
                    }
                    
                    // Agregamos la imagen nueva y guardamos el nombre de la imagen nueva en los datos.
                    $nombreImagen=$ventaDB->addImagen($imagen);
                    $datos["img_venta"]=$nombreImagen . ".jpg";
                    $item=$ventaDB->update($datos, $id);
                }
                
                break;
            case "post_vendidos":
                $ventaDB=new Venta();
                $item=$ventaDB->updateCompra($datos);
                break;
            default:
                echo "Entity Error";
                break;
        }

        if($item->rowCount()==1){
            echo json_encode(["result"=>"ok"]);
        }else{
            echo json_encode(["result"=>"fail"]);
        }
    }

    /**
     * Añade un nuevo dato a la base de datos según la entidad indicada.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - entidad: tipo de dato a crear (usuarios, juegos, reviews, productos, post_vendidos).
     *  - datos: JSON con los campos del nuevo dato (se desinfectan contra inyección).
     *  - img (opcional): archivo de imagen para asociar al nuevo dato.
     *
     * Para usuarios y productos, si se proporciona imagen, la guarda y asocia.
     * Para usuarios, genera listas básicas después de crear el usuario.
     * Para post_vendidos usa un método específico para crear la compra.
     *
     * En caso de error en la entidad, imprime mensaje de error.
     *
     * @return void
     */
    public function addDato(){

        if(empty($_SESSION)){
            Security::closeSession();
        }

        $entidad=Validators::evitarInyeccion($_POST["entidad"]);
        $datos=json_decode($_POST["datos"], true);
        
        if($entidad=="post_vendidos"){
            unset($datos["id_PostAntiguo"]);
            unset($datos["id_CompradorAntiguo"]);
            unset($datos["FechaAntigua"]);
        } else if($entidad == "usuarios"){

        }else if($entidad == "productos"){

        }

        switch ($entidad) {
            case "usuarios":
                $usuarioDB=new Usuario();
                $listaDB=new Lista();

                $imagen = $_FILES['img'] ?? null; // Obtener la imagen si se proporciona

                if ($imagen != null) {
                    $Nombreimagen=$usuarioDB->addImagen($imagen);
                }

                if(!empty($imagen)){
                    // Lógica para registrar al usuario
                    $item=$usuarioDB->register($datos["Nombre"], $datos["Apellido"], $datos["Correo"], "1234Prueba@", $datos["Nick"], $datos["Direccion"], $Nombreimagen.".jpg");
                }else{
                    $item=$usuarioDB->register($datos["Nombre"], $datos["Apellido"], $datos["Correo"], "1234Prueba@", $datos["Nick"], $datos["Direccion"]);
                }
                $listaDB->creaListasBasicas($datos["Nick"], $item); // Crear listas basicas del usuario.
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
                $imagen = $_FILES['img'] ?? null; // Obtener la imagen si se proporciona

                if ($imagen != null) {
                    $Nombreimagen=$ventaDB->addImagen($imagen);
                }

                if(!empty($imagen)){
                    $datos["img_venta"]=$Nombreimagen . ".jpg";
                }
                $item=$ventaDB->create($datos);
                break;
            case "post_vendidos":
                $ventaDB=new Venta();
                $result=$ventaDB->crearCompra($datos);
                break;
            default:
                echo "Entity Error";
                break;
        }
    }

    /**
     * Obtiene una lista paginada de datos para administración según la entidad indicada.
     *
     * Requiere sesión activa y que el usuario sea administrador.
     * Recibe por POST:
     *  - pagina: número de página actual.
     *  - limite: cantidad de registros por página.
     *  - inicio: índice desde donde iniciar la consulta.
     *  - entidad: tipo de datos a listar (usuarios, juegos, reviews, productos, post_vendidos).
     *  - busqueda (opcional): término para filtrar resultados.
     *
     * Soporta búsqueda con comodines (%).
     * Para la entidad "post_vendidos" utiliza métodos específicos para contar y listar.
     * Devuelve en JSON:
     *  - columnas: nombres de columnas del resultado.
     *  - datos: registros obtenidos.
     *  - pagina: página actual.
     *  - total_paginas: total de páginas disponibles.
     *
     * En caso de entidad inválida, muestra error.
     *
     * @return void Envía JSON con los datos solicitados.
     */
    public function listaAdmin(){

        if(empty($_SESSION) || !$_SESSION["Admin"]) {
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

    /**
     * Cambia la contraseña de un usuario determinado.
     *
     * Requiere sesión activa.
     * Recibe por POST:
     *  - id_usuario: ID del usuario al que cambiar la contraseña.
     *  - Pass: nueva contraseña (en texto plano, se debe manejar hasheo internamente).
     *
     * Retorna JSON con:
     *  - result: "ok" si la actualización fue exitosa, "fail" si no.
     *  - Columnas afectadas: número de filas modificadas (en caso de fallo).
     *
     * @return void Envía JSON con resultado de la operación.
     */
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

    /**
     * Obtiene los géneros y plataformas asociados a un juego específico.
     *
     * Requiere sesión activa y que el usuario sea administrador.
     * Recibe por POST:
     *  - id_juego: ID del juego para obtener sus géneros y plataformas.
     *
     * Retorna JSON con:
     *  - generos: lista de géneros asociados.
     *  - plataformas: lista de plataformas asociadas.
     *
     * @return void Envía JSON con géneros y plataformas del juego.
     */
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

    /**
     * Actualiza los géneros y plataformas asociados a un juego específico.
     *
     * Requiere sesión activa y que el usuario tenga permisos de administrador.
     * Recibe por POST:
     *  - id_juego: ID del juego al que se le actualizarán géneros y plataformas.
     *  - generos: arreglo con los IDs de géneros a asociar.
     *  - plataformas: arreglo con los IDs de plataformas a asociar.
     *
     * El método elimina primero todos los géneros y plataformas existentes para el juego.
     * Luego intenta insertar los nuevos géneros y plataformas recibidos.
     * 
     * Si ocurre un fallo al insertar géneros, se revierten los cambios a los géneros anteriores.
     * Si ocurre un fallo al insertar plataformas, se revierten los cambios a las plataformas anteriores.
     *
     * Devuelve JSON con:
     *  - result: "ok" si la operación fue exitosa,
     *            "fail" si hubo un error, junto con un mensaje explicativo.
     *
     * @return void Envía JSON con el resultado de la actualización.
     */
    public function addGenerosPlataformas(){
        if(empty($_SESSION) || !$_SESSION["Admin"]) {
            Security::closeSession();
        }
        
        $generoDB=new Genero();
        $plataformaDB=new Plataforma();
        $id_juego=Validators::evitarInyeccion($_POST["id_juego"]);

        $generosAntiguos=$generoDB->getGenerosIDJuegoById($id_juego);
        $plataformasAntiguas=$plataformaDB->getPlataformasIDJuegobyId($id_juego);

        if(!$generoDB->borrarGenerosJuego($id_juego)){
            echo json_encode(["result"=>"fail", "mensaje"=>"Failed to restart Genres"]);
            exit();
        }

        if(!$plataformaDB->borrarPlataformasJuego($id_juego)){
            echo json_encode(["result"=>"fail", "mensaje"=>"Failure to restart Platforms"]);
            exit();
        }
        
        

        $generos=[];
        foreach($_POST["generos"] as $genero){
            $generoComprobado=Validators::evitarInyeccion($genero);

            if(!$generoDB->insertarGenerosJuego($id_juego, $generoComprobado)){

                $generoDB->borrarGenerosJuego($id_juego);

                foreach($generosAntiguos as $generoAntiguo){
                    $generoDB->insertarGenerosJuego($id_juego, $generoAntiguo);
                }
                
                echo json_encode(["result"=>"fail", "mensaje"=>"Failed to insert new Genres. Genres reset"]);
                exit();
            }
            
        }

        $plataformas=[];
        foreach($_POST["plataformas"] as $plataforma){
            $plataformaComprobada=Validators::evitarInyeccion($plataforma);

            if(!$plataformaDB->insertarPlataformasJuego($id_juego, $plataformaComprobada)){

                $plataformaDB->borrarPlataformasJuego($id_juego);

                foreach($plataformasAntiguas as $plataformaAntigua){
                    $plataformaDB->insertarPlataformasJuego($id_juego, $plataformaAntigua);
                }

                echo json_encode(["result"=>"fail", "mensaje"=>"Error inserting new platforms. Platforms restarted."]);
                exit();
            }
            
        }

        echo json_encode(["result"=>"ok"]);
    }
}