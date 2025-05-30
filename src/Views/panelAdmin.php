<?php

$css = 'panelAdmin';
require_once __DIR__ . '\Templates\inicio.php';
include_once __DIR__. '\Templates\barra-lateral.admin.php';
?>

<div class="content">
    <h1>Panel de Administrador</h1>
    <a class="enlace-vuelta enlace m-3" href="/TDG/ ">< Volver a Inicio</a>

    <a class="boton-tabla" href="/TDG/panelAdmin/tabla?tabla=usuarios">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-user"></i>
            </div>
            <h4>Administrar Usuarios</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/TDG/panelAdmin/tabla?tabla=juegos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-user"></i>
            </div>
            <h4>Administrar Juegos</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/TDG/panelAdmin/tabla?tabla=reviews">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-regular fa-comment"></i>
            </div>
            <h4>Administrar Reviews</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/TDG/panelAdmin/tabla?tabla=productos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-rectangle-list"></i>
                <i class="fa-solid fa-store"></i>
            </div>
            <h4>Administrar Productos</h4>
        </div>
    </a>

    <a class="boton-tabla" href="/TDG/panelAdmin/tabla?tabla=post_vendidos">
        <div class="contenedor-admin">
            <div class="icono">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h4>Administrar Ventas</h4>
        </div>
    </a>
    

</div>

<?php
require_once __DIR__ . '\Templates\final.php';
?>