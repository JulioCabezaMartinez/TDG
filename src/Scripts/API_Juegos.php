<?php
namespace App\Scripts\API;
use App\Models\Juego;

header("Content-Type: application/json");

// Comprobamos si ha cambiado el numero de juegos en la base de datos de la API.
$url="https://api.rawg.io/api/games?key=".$_ENV["API_KEY"]."&page=1&page_size=1";
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
$countAPI = $data['count'];

$juego = new Juego();
$numero_juegos_bd = $juego->getCount();

if($countAPI != $numero_juegos_bd){
    // Si el número de juegos ha cambiado, recogemos los nuevos juegos de la API de RAWG.io.
    // Recoger los juegos de la API de RAWG.io.

    $juego->rellenarBD(countAPI: $countAPI);
}
    
?>