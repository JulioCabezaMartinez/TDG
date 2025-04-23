<?php
namespace App\Scripts\API;
use App\Models\Plataforma;

header("Content-Type: application/json");

// Recoger los generos de la API de RAWG.io.
$url="https://api.rawg.io/api/platforms?key=".$_ENV["API_KEY"];
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
$plataformas = $data['results'];
$plataforma = new Plataforma();

foreach($plataformas as $plataformaAPI){
    // Comprobar si el genero ya existe en la base de datos
    $plataforma_bd = $plataforma->getById($plataformaAPI['id']);

    if($plataforma_bd!=null){
        continue; // Si el genero ya existe, saltar a la siguiente plataforma
    }
    
    // Insertar el genero en la base de datos
    $plataforma->rellenarBDPlataformas($plataformaAPI['id'], $plataformaAPI['name']);
}

?>