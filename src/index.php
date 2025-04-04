<?php
use App\Controllers\UserController;
use App\Controllers\NoteController;

// Obtener la ruta
$route = $_GET['route'] ?? 'user/index';
$id = $_GET['id'] ?? null;


// Controlador frontal que maneja la ruta
switch ($route) {
    case '':
        break;

    default:
        echo "Ruta no encontrada.";
}