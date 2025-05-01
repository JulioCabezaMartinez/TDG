<?php
require_once 'vendor\autoload.php'; // Esto debe estar al principio del archivo para cargar las dependencias de Composer
use Dotenv\Dotenv;
use App\Core\Router;

// Cargar el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Crear una instancia del enrutador
$router = new Router();

// Ruta Principal
$router->add('/', 'ControllerHome@index');

// Rutas de Juegos
$router->add('/juegos', 'ControllerJuego@lista_juegos');
$router->add('/juegos/view', 'ControllerJuego@view_juego');

// Rutas de Ventas
$router->add('/ventas', 'ControllerVenta@lista_ventas');
$router->add('/ventas/view', 'ControllerVenta@view_venta');

// Rutas del Perfil
$router->add('/perfil', 'ControllerUsuario@perfil_listas');
$router->add('/perfil/lista', 'ControllerUsuario@lista');
$router->add('/perfil/ventas', 'ControllerUsuario@ventas');


// No entiendo como funciona el enrutador, pero aquí se define la ruta para la página de inicio

// Procesar la solicitud
$router->dispatch($_SERVER['REQUEST_URI']); // Despachar la ruta correspondiente
?>