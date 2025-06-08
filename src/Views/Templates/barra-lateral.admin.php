
<div class="barra-lateral">
    <a class="enlace m-3" href="/">< Volver a Inicio</a>
    <ul>
        <h5>Tablas: </h5>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "usuarios"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/panelAdmin/tabla?tabla=usuarios">Users</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/panelAdmin/tabla?tabla=usuarios">Users</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "juegos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/panelAdmin/tabla?tabla=juegos">Games</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/panelAdmin/tabla?tabla=juegos">Games</a></li>
        <?php
            }
        ?>
        
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "reviews"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/panelAdmin/tabla?tabla=reviews">Reviews</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/panelAdmin/tabla?tabla=reviews">Reviews</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "productos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/panelAdmin/tabla?tabla=productos">Products</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/panelAdmin/tabla?tabla=productos">Products</a></li>
        <?php
            }
        ?>
        <?php
            if(isset($_GET["tabla"]) && $_GET["tabla"] == "post_vendidos"){
        ?>
            <li class="tabla-actual"><a class="enlace" href="/panelAdmin/tabla?tabla=post_vendidos">Sales</a></li>
        <?php
            }else{
        ?>
            <li><a class="enlace" href="/panelAdmin/tabla?tabla=post_vendidos">Sales</a></li>
        <?php
            }
        ?> 
        <a href="campo"></a>
    </ul>
</div>
