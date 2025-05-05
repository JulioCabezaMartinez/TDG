<?php
$css = 'checkout';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<div class="cuerpo">
    <div class="metodo_pago">
        <h2>Metodo de Pago</h2>
        <div>
            <img src="https://www.instant-gaming.com/themes/igv2/images/icons/payments/icon-paypal.svg" alt=""> <!-- Imagen de Paypal -->
            <h3>Paypal</h3>
        </div>
    </div>
    <div class="resumen_compra">
        <h2>Resumen</h2>
        <div>
            <div>
                <p><strong><?php echo $venta["Titulo"] ?></strong></p>
                <p> <?php echo $venta["Precio"] ?> €</p>
            </div>

            <div class="precios">
                <input type="hidden" id="id_producto" value="<?php echo $venta["id"] ?>">
                <p>Coste de Gestion:</p>
                <p>€</p> <!-- Coste de gestión -->
                <p>Precio del producto:</p>
                <p> <?php echo $venta["Precio"] ?> €</p>

                <p>Total:</p>
                <p id="precio"> <?php echo $venta["Precio"] //echo $venta["Precio"] + $precio_gestion ?> €</p>

                <div id="paypal-buttons"></div> <!-- Botones de Paypal -->
            </div>

        </div>
    </div>
</div>

<script>
    paypal.Buttons({
        fundingSource: paypal.FUNDING.PAYPAL,
        style: {
            color: "blue",
            shape: "pill",
            label: "pay",
        },

        // Función para crear la orden
        createOrder: function(data, actions) {
            let texto_precio=$("#precio").text().trim();
            let precio=texto_precio.split("€")[0].trim();

            let id_producto=$("#id_producto").val();
            // Hacer la solicitud al backend para crear la orden
            return fetch('/TDG/src/AJAX/AJAX.paypal.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        productoId: id_producto,
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json()) // Respuesta del backend que debe incluir 'orderID'
                .then(orderData => {
                    console.log('Respuesta de la API de PayPal:', orderData);
                    // Verifica si el orderID está presente
                    if (!orderData.orderID) {
                        throw new Error("Se esperaba un orderID en la respuesta.");
                    }
                    return orderData.orderID; // Asegúrate de devolver el orderID
                });
        },

        // Función para manejar el pago
        onApprove: function(data, actions) {
            // Después de que el usuario autorice el pago, completar la transacción
            return actions.order.capture().then(function(details) {
                if (details.status !== 'COMPLETED') {
                    throw new Error("El pago no se completó correctamente.");
                } else {
                    alert('¡Pago realizado con éxito!');
                    console.log('Detalles del pago: ', details); // Puedes personalizar la respuesta
                }
            });
        },

        onCancel: function(data) {
            // Función para manejar la cancelación del pago
            alert('El pago ha sido cancelado.'); // Puedes personalizar el mensaje
        },

        // Función para manejar los errores
        onError: function(err) {
            alert("Hubo un error en el proceso de pago.");
        }

    }).render('#paypal-buttons');
    // This function displays Smart Payment Buttons on your web page.
</script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>