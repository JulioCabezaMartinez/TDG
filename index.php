<?php
session_start();

require_once 'vendor\autoload.php'; // Esto debe estar al principio del archivo para cargar las dependencias de Composer
use Dotenv\Dotenv;
use App\Core\Router;

// Cargar el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Crear una instancia del enrutador
$router = new Router();

// Rutas Principales
$router->add('/', 'ControllerHome@index');
$router->add('/login', 'ControllerHome@login');
$router->add('/register', 'ControllerHome@register');
$router->add('/registrar-usuario', 'ControllerHome@registrarUsuario');

//Rutas de Administrador
$router->add('/panelAdmin', 'ControllerHome@admin');
$router->add('/panelAdmin/tabla', 'ControllerAdmin@tabla');

// Rutas de Juegos
$router->add('/juegos', 'ControllerJuego@lista_juegos');
$router->add('/juegos/view', 'ControllerJuego@view_juego');

// Rutas de Reviews
$router->add('/juegos/view/review', 'ControllerReview@lista_reviews_juego');

// Rutas de Ventas
$router->add('/ventas', 'ControllerVenta@lista_ventas');
$router->add('/ventas/view', 'ControllerVenta@view_venta');

//Rutas Compra
$router->add('/ventas/view/checkout', 'ControllerVenta@checkout');
$router->add('/ventas/view/finalizacionCompra', 'ControllerVenta@finalizacion_compra');


// Rutas de Perfil
$router->add('/perfil', 'ControllerUsuario@perfil_listas');
$router->add('/perfil/lista', 'ControllerUsuario@lista');
$router->add('/perfil/ventas', 'ControllerUsuario@ventas');

//Rutas AJAX
$router->add('/AJAX/lista_juegos', 'ControllerAJAX@lista_juegos');
$router->add('/AJAX/lista_ventas', 'ControllerAJAX@lista_ventas');
$router->add('/AJAX/lista_review', 'ControllerAJAX@lista_review');
$router->add('/AJAX/addJuegoLista', 'ControllerAJAX@addJuegoLista');
$router->add('/AJAX/eliminarJuegoLista', 'ControllerAJAX@eliminarJuegoLista');

    //AJAX de LogIn/Out
    $router->add('/AJAX/CompruebaLogin', 'ControllerAJAX@compruebaLogin');
    $router->add('/AJAX/logout', 'ControllerAJAX@logout');

    //AJAX del Admin
    $router->add('/AJAX/eliminarDato', 'ControllerAJAX@eliminarDato');
    $router->add('/AJAX/datosModificarDato', 'ControllerAJAX@datosModificarDato');
    $router->add('/AJAX/modificarDato', 'ControllerAJAX@modificarDato');

// Procesar la solicitud
$router->dispatch($_SERVER['REQUEST_URI']); // Despachar la ruta correspondiente
?>