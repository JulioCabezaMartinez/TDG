<?php
require_once '../../vendor/autoload.php'; // Esto debe estar al principio del archivo
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Ajusta el path a la raíz del proyecto
$dotenv->load(); // Esto carga las variables al entorno
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo $_ENV["PAYPAL_SRC"] ?>"></script>
    <title>Document</title>
</head>
<body>
    <div id="paypal-buttons"></div>

    <script>
        paypal.Buttons({
            style:{
                color: "blue",
                shape: "pill",
                label: "pay",
            }
        }).render('#paypal-buttons');
        // This function displays Smart Payment Buttons on your web page.
    </script>
</body>
</html>