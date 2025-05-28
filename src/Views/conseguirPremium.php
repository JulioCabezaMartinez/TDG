<?php
$css = 'conseguirPremium';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h1>Premium</h1>

<h4>Ventajas TDG Premium</h4>

<br><br>

<ul>
    <li>Productos a la venta de Usuarios premium aparecen antes en la lista de Productos a la venta</li>
    <br><br>
    <li>Gastos de Gestion en las compras GRATIS</li>
    <br><br>
    <li>Por solo 15 € al mes</li>
</ul>

<a href="/TDG/ventas/view/checkout?id=<?php echo -1 ?>">
    <button class="boton-perso">Conseguir Premium <i class="fa-solid fa-crown"></i></button>
</a>


<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '\Templates\final.php';
?>