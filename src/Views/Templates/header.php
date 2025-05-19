<header>
    <i class="fa-solid fa-bars"></i>

    <h3>To Do Games</h3>

    <div class="izq">
        <!-- Logo de la app -->
        <a href="/TDG/">
            <img class="logo-TDG" src="/TDG/public/IMG/TDG-Logo.png" alt="">
        </a>
    </div>

</header>

<div class="menu-hamburguesa">
    <div>
        <i class='fa-solid fa-xmark cerrar-hamburguesa'></i>
        <a href="/TDG/"><img class="logo-TDG" src="/TDG/public/IMG/TDG-Logo.png" alt=""></a>
    </div>

    <?php
    if(!empty($_SESSION)){
    ?>

        <h4>Bienvenido, <?php echo $_SESSION["Nick"] ?></h4>
        <br><br>
        <a href="/TDG/AJAX/logout" id="logout-btn" class="btn btn-primary">Cerrar Sesion</a>

    <?php
    }else{
    ?>
        <div>
            <a class="btn btn-primary" href="/TDG/login" class="login">Iniciar Sesión</a>
            <a class="btn btn-secondary" href="/TDG/register" class="register">Registrarme</a>
        </div>
    <?php
    }
    if(!empty($_SESSION) && $_SESSION["Admin"]){
    ?>
            <a class="btn btn-secondary" href="/TDG/panelAdmin">Panel de Administrador</a>
    <?php
    }
    ?>


    
    <div class="d-flex justify-content-start align-items-center m-4">
        <h4 for="neon">NEON: </h4>
        <select name="neon" id="neon_cookie" class="form-select w-25 ms-3">
            <option value="#FFFFFF">Blanco</option>
            <option value="#9f00c7">Morado</option>
            <option value="#0099ff">Azul</option>
            <option value="#ff0000">Rojo</option>
        </select>
        <button class="btn btn-primary ms-3" id="btn-neon">Guardar</button>
    </div>

    <hr>

    <div class="links-hamburguesa">

        <div class="busqueda-juego">
            <h2>Buscar Juego: </h2>
            <div class="input-group">
                <input id="busquedaJuego" class="form-control" type="text">
                <button id="btnBusquedaJuego" class="btn btn-outline-secondary bg-white" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
        </div>
        <br>
        <a href="/TDG/">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-house"></i>
                <h2>HOME</h2>
            </div>
        </a>
        <br>
        <a href="/TDG/juegos">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-gamepad"></i>
                <h2>JUEGOS</h2>
            </div>
        </a>
        <br>
        <a href="/TDG/ventas">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-store"></i>
                <h2>VENTAS</h2>
            </div>
        </a>
    </div>
</div>