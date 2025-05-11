<?php
$css = 'lista_ventas';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 style="text-align: center;">Ventas</h3>

<div class="filtros_desplegable">
    <div class="filtros_texto">
        <p>Filtros</p>
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
    </div>

    <div class="filtros_opciones">
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion1" name="opcion1" value="opcion1">
            <label for="opcion1">Opción 1</label>
        </div>
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion2" name="opcion2" value="opcion2">
            <label for="opcion2">Opción 2</label>
        </div>
        <div class="opcion_filtro">
            <input type="checkbox" id="opcion3" name="opcion3" value="opcion3">
            <label for="opcion3">Opción 3</label>
        </div>
    </div>
</div>

<div id="filtros">
    <button id="boton_filtro" class="btn_filtros">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>

    <!-- <hr> Poner una barra en vertical para separar el boton de los filtros -->

    <!-- Carrusel de Filtros activos -->
    <div class="swiper">
        <div class="card-wrapper">
            <div class="filtros_activos card-list swiper-wrapper">

            </div>
        </div>
    </div>

</div>

<div class="centrar-di">
    <div class="lista_ventas">
        
        <?php
        foreach ($lista_ventas as $venta) {
        ?>
        <a href="/TDG/ventas/view/checkout?id=<?php echo $venta["id"] ?>">
            <div id="<?php echo "{$venta['id']}" ?>" class='venta'>
                
                    <img src='/TDG/public/IMG/<?php echo $venta["img_venta"] ?>' alt=''>

                    <div class='info_juego'>
                        <h1><?php echo $venta['Titulo']?></h1>
                        <p class='precio'><strong><?php echo $venta['Precio']?>€</strong></p>
                    </div>
                </div>
        </a>
        <?php
        }
        ?>
    </div>
</div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<script>
    $(document).ready(function(){
        $("#boton_filtro").click(function() {
            console.log("click");
            $(".filtros_desplegable").toggleClass("active");
        });
    });
</script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>