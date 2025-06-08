<?php

$css = 'panelAdmin';
require_once __DIR__ . '/Templates/inicio.php';
include_once __DIR__. '/Templates/barra-lateral.admin.php';
?>

<div class="content">
    <h1>Administrator Panel</h1>
    <a class="enlace-vuelta enlace m-3" href="/ ">< Back to Home</a>

    <a class="boton-tabla" href="/panelAdmin/tabla?tabla=usuarios">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-user"></i>
            </div>
            <h4>Manage Users</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/panelAdmin/tabla?tabla=juegos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-user"></i>
            </div>
            <h4>Manage Games</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/panelAdmin/tabla?tabla=reviews">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-regular fa-comment"></i>
            </div>
            <h4>Manage Reviews</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/panelAdmin/tabla?tabla=productos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-store"></i>
            </div>
            <h4>Manage Products</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/panelAdmin/tabla?tabla=post_vendidos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h4>Manage Sales</h4>
        </div>
    </a>
    

</div>

<?php
require_once __DIR__ . '/Templates/final.php';
?>