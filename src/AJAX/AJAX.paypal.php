<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use Dotenv\Dotenv;
use App\Models\Venta;

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$clientId = $_ENV['PAYPAL_CLIENT_ID'];
$clientSecret = $_ENV['PAYPAL_CLIENT_SECRET'];
$body = json_decode(file_get_contents('php://input'), true); //php://input permite leer el cuerpo de la solicitud POST cuando es un JSON.
$productoId = $body['productoId'] ?? null;

if($productoId != $_SESSION["id_venta"]){
    echo json_encode(["error" => "La Id del producto ha sido modificada"]);
    exit;
}

$ventaBD=new Venta();

$precio_producto = $ventaBD->getById($productoId)["Precio"];


if($_SESSION["Premium"]==true){
    $precio=$precio_producto;
}else{
    $precio=$precio_producto + 2.99;
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
    echo json_encode(['error' => 'No se pudo obtener el token de PayPal']);
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
    echo json_encode(['error' => 'No se pudo crear la orden']);
}
