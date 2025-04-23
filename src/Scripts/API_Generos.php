<?php
namespace App\Scripts\API;
use App\Models\Genero;

header("Content-Type: application/json");

// Recoger los generos de la API de RAWG.io.
$url="https://api.rawg.io/api/genres?key=".$_ENV["API_KEY"];
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Content-Type: application/json'
));
$response = curl_exec($ch);
curl_close($ch);

    
// Decodificar la respuesta JSON    
$data = json_decode($response, true);
$generos = $data['results'];
$genero = new Genero();

foreach($generos as $generoAPI){
    // Comprobar si el genero ya existe en la base de datos
    $genero_bd = $genero->getById($generoAPI['id']);

    if($genero_bd == $generoAPI['id']){
        continue; // Si el genero ya existe, saltar al siguiente genero
    }

    // Obtener el ID del genero
    $id_genero = $generoAPI['id'];
    
    // Insertar el genero en la base de datos
    $genero->rellenarBDGeneros($id_genero, $generoAPI['name']);
}

?>