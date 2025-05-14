<?php
$css = 'panelAdmin';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\barra-lateral.admin.php';
?>

<div class="content">
    <h2>Tabla <?php echo $entidad ?></h2>
    <table class="table table-striped table-dark tabla-datos">
        <thead>
            <tr>
                <?php
                foreach ($columnas as $columna) {
                ?>
                    <th><?php echo $columna ?></th>
                <?php
                }
                ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($lista as $item) {
                echo '<tr>';
                foreach ($item as $campo) {

                    if (strlen($campo) >= 20) {
                        $campo = str_split($campo, 20)[0];
                        $campo .= "...";
                    }
            ?>
                    <td><?php echo $campo ?></td>
            <?php
                }
                echo '<td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item btn btn-danger">Eliminar</button>
                                    <button class="dropdown-item btn btn-primary">Modificar</button>';

                if ($entidad == "usuarios") {
                    echo '<button class="dropdown-item btn btn-primary">Ver Listas</button>';
                }

                echo '</div>
                            </div>
                        </td>
                    </tr>';
            }
            ?>

            </tr>


        </tbody>
    </table>

    <div class="tabla-movil">
        <?php
        foreach ($lista as $item) {
            echo '<div class="fila">';
            $contador = 0;
            // Iterar sobre cada celda de la fila
            foreach ($item as $index => $campo) {
                // Obtener el nombre de la columna correspondiente al índice
                $columna = $columnas[$contador];
                if (strlen($campo) >= 20) {
                    $campo = str_split($campo, 20)[0];
                    $campo .= "...";
                }

                echo '<div class="columna">';
                echo '<div class="header">' . $columna . '</div>';
                echo '<div class="contenido">' . strip_tags($campo) . '</div>';
                echo '</div>';
                $contador++;
            }
            $contador = 0;
        ?>
            <div class="columna columna-final">
                <div class="header">Acciones</div>
                <div class="contenido">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item btn btn-danger">Eliminar</button>
                            <button class="dropdown-item btn btn-primary">Modificar</button>
                            <?php
                            if ($entidad == "usuarios") {
                                echo '<button class="dropdown-item btn btn-primary">Ver Listas</button>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
    </div><!-- // Cerrar div.fila -->
<?php
        }
?>
</div>
<?php
include_once __DIR__ . "./Templates/footer.php";
?>
<script>
    window.addEventListener('resize', updateContentMargin);

    function updateContentMargin() {
        console.log("Cambiando tamaño");
        const sidebarWidth = document.querySelector('.barra-lateral').offsetWidth;
        console.log("Tamaño sideBar= " + sidebarWidth);
        document.querySelector('.content').style.marginLeft = `${sidebarWidth}px`;
    }

    //Llamar la función al cargar la página para asegurarse de que se ajuste desde el principio
    updateContentMargin();
</script>
<?php
require_once __DIR__ . '\Templates\final.php';
?>