<?php
$css = 'conseguirPremium';
require_once __DIR__ . '/Templates/inicio.php';

require_once __DIR__ . '/Templates/header.php';
?>

<div class="premiumImg">
    <h1>Premium</h1>
    <img class="ms-4" src="/public/IMG/Productos-img/PremiumTDG.png">
</div>

<div class="p-6 rounded-xl shadow-md max-w-md mx-auto">
  <h4 class="text-xl font-semibold text-gray-800 mb-4">TDG Premium Advantages</h4>
  <br><br><br>
  <ul class="space-y-4 text-gray-700 list-disc list-inside">
    <li><strong>Priority Listings:</strong> Premium Users' Products appear first in the Products Sale list.</li>
    <br><br>
    <li><strong>Zero Fees:</strong> FREE Management Fees on all your purchases.</li>
    <br><br>
    <li><strong>Affordable Price:</strong> Just €15 a month.</li>
  </ul>
</div>

<a href="/ventas/view/checkout?id=<?php echo -1 ?>">
    <button class="btn-premium boton-perso">Get Premium <i class="fa-solid fa-crown"></i></button>
</a>


<?php
    include_once __DIR__. "/./Templates/footer.php";
?>

<?php
require_once __DIR__ . '/Templates/final.php';
?>