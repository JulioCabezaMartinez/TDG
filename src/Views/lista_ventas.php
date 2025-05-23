<?php
$css = 'lista_ventas';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\header.php';
?>

<h3 class="mt-4" style="text-align: center;">Catálogo</h3>

<div id="filtros">
    <button id="boton_filtro" class="btn_filtros boton-perso">
        <i id="filtros_icon" class="fa-solid fa-filter"></i>
        <p>Filtros</p>
    </button>

    <!-- <hr> Poner una barra en vertical para separar el boton de los filtros -->

    <!-- Carrusel de Filtros activos
    <div class="swiper">
        <div class="card-wrapper">
            <div class="filtros_activos card-list swiper-wrapper">

            </div>
        </div>
    </div> -->

</div>

<!-- Menu de Filtros -->
<div class="filtros_desplegable">
    <div class="filtros_opciones">
        <div>
           <label for="nombre">Nombre del Juego:</label>
            <input class="form-control" id="nombreInput" type="text">
        </div>
        <br>
        <div>
            <label for="stock">Stock:</label>
            <br>
            <label for="si">Sí</label>
            <input class="form-check-input" type="radio" id="si" name="stock" value="si">

            <label for="no">No</label>
            <input class="form-check-input" type="radio" id="no" name="stock" value="no">
        </div>
        <br>
        <!-- Se puede poner año de salida con este SQL: WHERE YEAR(Anyo_salida) = ?; -->
        <div>
            <label for="">Precio Máximo: </label>
            <br>
            <input class="form-control w-50" id="precioMaxInput" type="number">
        </div>
        <br>
        <div>
            <label for="">Precio Mínimo: </label>
            <br>
            <input class="form-control w-50" id="precioMinInput" type="number">
        </div>
        <br>
        <div>
            <label for="">Consola: </label>
            <br>
            <select class="form-select" id="ConsolaInput">
                <option value="" selected disabled>Selecciona una consola</option>
                <?php
                foreach($lista_plataformas as $plataforma){
                ?>
                <option value="<?php echo $plataforma["id"]?>"><?php echo $plataforma["nombre"]?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <br>
        <div>
            <label for="">Estado: </label>
            <br>
            <select class="form-select" id="EstadoInput">
                <option value="" selected disabled>Selecciona un estado</option>
                <option value="Nuevo">Nuevo</option>
                <option value="Buen Estado">Buen Estado</option>
                <option value="Usado">Usado</option>
            </select>
        </div>
        <br>
        <p id="resetFiltros" class="enlace">Quitar filtros</p>
        <br>
        <button id="aplicarFiltros" class="btn btn-primary w-50">Filtrar</button>
        
    </div>
</div>
<!-- Menu de Filtros -->


<div class="paginacion"></div>

<div class="centrar-div">
    <div id="lista_ventas">
        
    </div>
</div>

<div class="paginacion"></div>

<?php
    include_once __DIR__. "./Templates/footer.php";
?>

<script src="/TDG/public/JS/lista_ventas.js" ></script>

<?php
require_once __DIR__ . '\Templates\final.php';
?>