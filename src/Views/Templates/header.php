<section id="hamBtn" class="hamBtn">
    <input type="checkbox" checked>
    <span class="linea1"></span>
    <span class="linea2"></span>
    <span class="linea3"></span>
</section>

<header>
    <div class="header-movil">
        <p></p>

        <a class="text-header-movil" href="/TDG/">
            <h3>To Do Games</h3>
        </a>

        <div class="izq">
            <!-- Logo de la app -->
            <a href="/TDG/">
                <img class="logo-TDG" src="/TDG/public/IMG/TDG-Logo.png" alt="">
            </a>
        </div>
    </div>
    <div class="header-escritorio">
        <a href="/TDG/">
            <img class="logo-TDG" src="/TDG/public/IMG/TDG-Logo.png" alt="">
        </a>

        <a href="/TDG/juegos">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-gamepad"></i>
                <h2 class="enlace">JUEGOS</h2>
            </div>
        </a>
        <a href="/TDG/ventas">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-store"></i>
                <h2 class="enlace">VENTAS</h2>
            </div>
        </a>
        <div class="input-group">
            <input id="busquedaJuego" class="form-control" type="text">
            <button id="btnBusquedaJuego" class="btn btn-outline-secondary bg-white" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <?php
        if (!empty($_SESSION)) {
        ?>
            <a href="/TDG/AJAX/logout" id="logout-btn" class="btn btn-primary">Cerrar Sesion</a>
        <?php
        } else {
        ?>
            <div>
                <a class="btn btn-primary" href="/TDG/login" class="login">Iniciar Sesión</a>
                o
                <a class="enlace" href="/TDG/register" class="register">Registrarse</a>
            </div>
        <?php
        }
        if (!empty($_SESSION) && $_SESSION["Admin"]) {
        ?>
            <a class="btn btn-secondary" href="/TDG/panelAdmin">Panel de Administrador</a>
        <?php
        }
        ?>
        <div id="opciones">
            <i class="fa-solid fa-chevron-down"></i>
            <i class="fa-solid fa-gear opciones"></i>
        </div>

    </div>
</header>


<div class="menu-hamburguesa">
    <div>
        <i class='fa-solid fa-xmark cerrar-hamburguesa'></i>
        <a href="/TDG/"><img class="logo-TDG" src="/TDG/public/IMG/TDG-Logo.png" alt=""></a>
    </div>

    <?php
    if (!empty($_SESSION)) {
    ?>

        <h4>Bienvenido, <?php echo $_SESSION["Nick"] ?></h4>
        <br><br>
        <a href="/TDG/AJAX/logout" id="logout-btn" class="btn btn-primary">Cerrar Sesion</a>

    <?php
    } else {
    ?>
        <div>
            <a class="btn btn-primary" href="/TDG/login" class="login">Iniciar Sesión</a>
            o
            <a class="enlace" href="/TDG/register" class="register">Registrarse</a>
        </div>
    <?php
    }
    if (!empty($_SESSION) && $_SESSION["Admin"]) {
    ?>
        <a class="btn btn-secondary" href="/TDG/panelAdmin">Panel de Administrador</a>
    <?php
    }
    ?>



    <div class="d-flex justify-content-start align-items-center m-4">
        <h4 for="neon">NEON: </h4>
        <select name="neon" id="neon-cookie-movil" class="form-select w-25 ms-3">
            <option value="#FFFFFF">Blanco</option>
            <option value="#9f00c7">Morado</option>
            <option value="#0099ff">Azul</option>
            <option value="#ff0000">Rojo</option>
        </select>
        <button class="btn btn-primary ms-3" id="btn-neon-movil">Guardar</button>
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

<div class="menu-hamburguesa-escritorio">

    <div class="d-flex justify-content-start align-items-center m-4">
        <h4 for="neon">NEON: </h4>
        <select name="neon" id="neon-cookie-escritorio" class="form-select ms-3">
            <option value="#FFFFFF">Blanco</option>
            <option value="#9f00c7">Morado</option>
            <option value="#0099ff">Azul</option>
            <option value="#ff0000">Rojo</option>
        </select>
        <button class="btn btn-primary ms-3" id="btn-neon-escritorio">Guardar</button>
    </div>

</div>