
<div class="barra-lateral">
    <a class="enlace m-3" href="/TDG/">< Volver a Inicio</a>
    <ul>
        <h5>Tablas: </h5>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "usuarios"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=usuarios">Usuarios</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=usuarios">Usuarios</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "juegos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=juegos">Juegos</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=juegos">Juegos</a></li>
        <?php
            }
        ?>
        
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "reviews"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=reviews">Reviews</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=reviews">Reviews</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "productos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=productos">Productos</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=productos">Productos</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "post_vendidos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=post_vendidos">Ventas</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/TDG/panelAdmin/tabla?tabla=post_vendidos">Ventas</a></li>
        <?php
            }
        ?> 
        <a href="campo"></a>
    </ul>
</div>
