<section id="hamBtn" class="hamBtn">
    <input type="checkbox" checked>
    <span class="linea1"></span>
    <span class="linea2"></span>
    <span class="linea3"></span>
</section>

<header>
    <div class="header-movil">
        <p></p>

        <a class="text-header-movil" href="/">
            <h3>To Do Games</h3>
        </a>

        <div class="izq">
            <!-- Logo de la app -->
            <a href="/">
                <img class="logo-TDG" src="/public/IMG/TDG-Logo.png" alt="">
            </a>
        </div>
    </div>
    <div class="header-escritorio">
        <a href="/">
            <img class="logo-TDG" src="/public/IMG/TDG-Logo.png" alt="">
        </a>

        <a href="/juegos">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-gamepad"></i>
                <h2 class="enlace">GAMES</h2>
            </div>
        </a>
        <a href="/ventas">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-store"></i>
                <h2 class="enlace">SHOP</h2>
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
        <a href="/perfil">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-user"></i>
                <h2 class="enlace">Profile</h2>
            </div>
        </a>
            <a href="/AJAX/logout" id="logout-btn" class="boton-perso">Log out</a>
        <?php
        } else {
        ?>
            <div>
                <a class="boton-perso" href="/login" class="login">Log in</a>
                o
                <a class="enlace" href="/register" class="register">Register</a>
            </div>
        <?php
        }
        if (!empty($_SESSION) && $_SESSION["Admin"]) {
        ?>
            <a class="boton-perso boton-perso-secundario" href="/panelAdmin">Administrator Panel</a>
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
        <a href="/"><img class="logo-TDG" src="/public/IMG/TDG-Logo.png" alt=""></a>
    </div>

    <?php
    if (!empty($_SESSION)) {
    ?>

        <h4>Bienvenido, <?php echo $_SESSION["Nick"] ?></h4>
        <br><br>
        <a href="/AJAX/logout" id="logout-btn" class="boton-perso">Log out</a>

    <?php
    } else {
    ?>
        <div>
            <a class="boton-perso" href="/login" class="login">Log in</a>
            o
            <a class="enlace" href="/register" class="register">Register</a>
        </div>
    <?php
    }
    if (!empty($_SESSION) && $_SESSION["Admin"]) {
    ?>
        <a class="boton-perso boton-perso-secundario" href="/panelAdmin">Administrator Panel</a>
    <?php
    }
    ?>

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
        <?php
        if (!empty($_SESSION)) {
        ?>
            <a href="/perfil">
                <div class="link-hamburguesa">
                    <i class="fa-solid fa-user"></i>
                    <h2 class="enlace">Profile</h2>
                </div>
            </a>
        <?php
        }
        ?>
        <br>
        <a href="/">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-house"></i>
                <h2>HOME</h2>
            </div>
        </a>
        <br>
        <a href="/juegos">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-gamepad"></i>
                <h2>GAMES</h2>
            </div>
        </a>
        <br>
        <a href="/ventas">
            <div class="link-hamburguesa">
                <i class="fa-solid fa-store"></i>
                <h2>SHOP</h2>
            </div>
        </a>
    </div>

    <div class="neon-cookies d-flex justify-content-start align-items-center">
        <h4 for="neon">NEON: </h4>
        <select name="neon" id="neon-cookie-movil" class="form-select w-25 ms-3">
            <option value="#9f00c7">Purple</option>
            <option value="#0099ff">Blue</option>
            <option value="#ff0000">Red</option>
        </select>
        <button class="boton-perso ms-3" id="btn-neon-movil">Save</button>
    </div>
</div>

<div class="menu-hamburguesa-escritorio">

    <div class="d-flex justify-content-start align-items-center m-4">
        <h4 for="neon">NEON: </h4>
        <select name="neon" id="neon-cookie-escritorio" class="form-select ms-3">
            <option value="#9f00c7">Purple</option>
            <option value="#0099ff">Blue</option>
            <option value="#ff0000">Red</option>
        </select>
        <button class="boton-perso ms-2" id="btn-neon-escritorio">Save</button>
    </div>

</div>