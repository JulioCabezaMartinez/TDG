<?php
require_once '..\vendor\autoload.php'; // Esto debe estar al principio del archivo
use Dotenv\Dotenv;

// Cargar el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ .'/..');
$dotenv->load();

// use App\Controllers\UserController;
// use App\Controllers\NoteController;

// // Obtener la ruta
// $route = $_GET['route'] ?? 'user/index';
// $id = $_GET['id'] ?? null;


// // Controlador frontal que maneja la ruta
// switch ($route) {
//     case '':
//         break;

//     default:
//         echo "Ruta no encontrada.";
// }

require_once __DIR__ . '\Scripts\API.php';