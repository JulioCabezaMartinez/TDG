<?php
require_once '..\vendor\autoload.php'; // Esto debe estar al principio del archivo
use Dotenv\Dotenv;

// Cargar el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ .'/..');
$dotenv->load();

// // Crear una instancia del enrutador
// $router = new Router();

// // Definir las rutas
// $router->add('/', 'ControllerHome@index');

require_once '../src/Scripts/API_Juegos.php';