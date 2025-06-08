<?php
$css = 'conseguirPremium';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<h1>Premium</h1>

<h4>TDG Premium Advantages</h4>

<br><br>

<ul>
    <li>Premium Users' Products for Sale appear first in the Products for Sale list</li>
    <br><br>
    <li>FREE Management Fees on Purchases</li>
    <br><br>
    <li>For only €15 a month</li>
</ul>

<a href="/ventas/view/checkout?id=<?php echo -1 ?>">
    <button class="boton-perso">Get Premium <i class="fa-solid fa-crown"></i></button>
</a>


<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>