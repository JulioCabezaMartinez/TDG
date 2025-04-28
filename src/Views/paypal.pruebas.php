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
    <title>TDG</title>
    <link rel="stylesheet" href="/public/CSS/style.css">
    <link rel="stylesheet" href="/public/CSS/header.css">
    <script src="<?php echo $_ENV["PAYPAL_SRC"] ?>"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- Swiper.js (Carrusel y paginación de este) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="/public/CSS/carrusel.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSS propio de cada vista -->
    <link rel="stylesheet" href="<?php echo "/public/CSS/{$css}.css" ?>">

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6af6746610.js" crossorigin="anonymous"></script>

</head>
<body>
    <div id="paypal-buttons"></div>

    <script>
        paypal.Buttons({
            style: {
                color: "blue",
                shape: "pill",
                label: "pay",
            },

            // Función para crear la orden
            createOrder: function(data, actions) {
                // Hacer la solicitud al backend para crear la orden
                return fetch('../AJAX/AJAX.paypal.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        productoId: '123'  // Aquí pasa el ID del producto o lo que sea necesario
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())  // Respuesta del backend que debe incluir 'orderID'
                .then(orderData => {
                    // Verifica si el orderID está presente
                    if (!orderData.orderID) {
                        throw new Error("Se esperaba un orderID en la respuesta.");
                    }
                    return orderData.orderID;  // Asegúrate de devolver el orderID
                });
    },

            // Función para manejar el pago
            onApprove: function(data, actions) {
                // Después de que el usuario autorice el pago, completar la transacción
                return actions.order.capture().then(function(details) {
                    if(details.status !== 'COMPLETED') {
                        throw new Error("El pago no se completó correctamente.");
                    }else{
                        alert('¡Pago realizado con éxito!');
                        console.log('Detalles del pago: ', details);  // Puedes personalizar la respuesta
                    }
                });
            },

            onCancel: function(data) {
                // Función para manejar la cancelación del pago
                alert('El pago ha sido cancelado.');  // Puedes personalizar el mensaje
            },

            // Función para manejar los errores
            onError: function(err) {
                console.log("Error en el pago: ", err);
                alert("Hubo un error en el proceso de pago.");
            }

        }).render('#paypal-buttons');
        // This function displays Smart Payment Buttons on your web page.
    </script>
</body>

</html>