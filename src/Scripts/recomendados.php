<?php 
require_once '../../vendor/autoload.php'; // Cargar el autoload de Composer para usar las variables de entorno
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Ajusta el path a la raíz del proyecto
$dotenv->load(); // Cargar las variables de entorno desde el archivo .env.

$usuarioId = $_SESSION['usuarioId'] ?? null; // Obtener el ID del usuario actual
if (!$usuarioId) {
    // Manejar el caso en que el usuario no está autenticado
    echo json_encode(['error' => 'Usuario no autenticado']);
    die();
}


$wishlist = $db->query("SELECT * FROM listas_usuarios WHERE usuario_id = ? & id_lista = ?", [$usuarioId, 3])->fetchAll(PDO::FETCH_ASSOC);
$wishlist = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // Simulación de la lista de juegos deseados por el usuario.

$generos_comprados; //Generos de los juegos deseados por el usuario.

for($i = 0; $i < count($wishlist); $i++){
    $generos_juegos=[];
    $genero_db = $db->query("SELECT * FROM juegos_generos WHERE id_juego = ?", [$wishlist[$i]])->fetch(PDO::FETCH_ASSOC);
    foreach( $genero_db as $genero) {
        array_push(array: $generos_juegos, values: $genero['id_genero']); 
    }

    array_push(array: $generos_comprados, values: $generos_juegos); // Agregar los géneros de cada juego a la lista de géneros comprados.
}

$generos_comprados = array_unique($generos_comprados); // Eliminar duplicados de la lista de géneros comprados.
$generos_comprados = array_values($generos_comprados); // Reindexar el array para que tenga índices consecutivos.
$generos_comprados = implode(',', $generos_comprados); // Convertir el array de géneros en una cadena separada por comas.

// Obtener la lista de juegos recomendados según los géneros comprados
$url="https://api.rawg.io/api/games?key={$_ENV['API_KEY']}&genres={$generos_comprados}&ordering=-rating&page_size=40"; // URL de la API de RAWG para obtener juegos recomendados por género.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Content-Type: application/json'
));
$response = curl_exec($ch);
curl_close($ch); // Cerrar la conexión cURL.

if ($response === false) {
    // Manejar el error de la solicitud a la API
    echo json_encode(['error' => 'Error al obtener datos de la API']);
    die();
}

$response = json_decode($response, true); // Decodificar la respuesta JSON de la API.

$games = $response['results'] ?? []; // Obtener la lista de juegos recomendados.

$games = array_filter($games, function($game) use ($wishlist) {
    return !in_array($game['id'], $wishlist); // Filtrar los juegos que ya están en la lista de deseos del usuario.
});
$games = array_values($games); // Reindexar el array de juegos recomendados.

echo json_encode($games); // Devolver la lista de juegos recomendados como respuesta JSON.

// Falta dar una vuelta a los juegos de la API cuando no se llene la lista de juegos en 10 elementos.


 //Algiritmo de recomendacion de juegos
    // 1. Obtener el ID del usuario actual
    // 2. Obtener la lista de juegos comprados por el usuario
    // 3. Obtener la lista de géneros de los juegos comprados
    // 4. Obtener la lista de juegos que pertenecen a esos géneros
    // 5. Filtrar los juegos para eliminar aquellos que ya han sido comprados por el usuario
    // 6. Mostrar la lista de juegos recomendados en la vista correspondiente
?>