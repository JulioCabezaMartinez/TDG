<?php
$css = 'tablaAdmin';
require_once __DIR__ . '\Templates\inicio.php';

require_once __DIR__ . '\Templates\barra-lateral.admin.php';
?>

<!-- Modal de creación de Review -->
<div class="modal fade" id="creacion_modificar_dato" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modificación <?php echo $entidad ?></h5>
            </div>
            <div class="modal-body">
                <?php
                    foreach($columnas as $columna){
                ?>
                    <div>
                        
                        <?php
                            if($columna=="Descripcion"){
                        ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <textarea class="form-control" id="<?php echo $columna ?>Input" rows="3"></textarea>
                        <?php
                            }elseif($columna=="id"){
                        ?>
                                <input type="hidden" id="<?php echo $columna ?>Input" value="">
                        <?php
                            }elseif($columna=="Imagen"){
                        ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?> (URL):</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">

                        <?php
                            }elseif($columna=="Anyo_salida"){
                        ?>
                                <label for="<?php echo $columna ?>Label"><strong>Año de Salida:</strong></label>
                                <input type="date" class="form-control" id="<?php echo $columna ?>Input">

                        <?php
                            }else{
                        ?>
                                <label for="<?php echo $columna ?>Label"><strong><?php echo $columna ?>:</strong></label>
                                <input type="text" class="form-control" id="<?php echo $columna ?>Input">
                        <?php
                            }
                        ?>
                        
                    </div>
                    <br>
                <?php        
                    }
                ?>
            </div>
            <div class="modal-footer">
                <button id="btn_cerrar_modal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn_modificar" type="button" class="btn btn-primary" data-dismiss="modal">Modificar Dato</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de Review -->

<div class="content">
    <input type="hidden" id="entidad" name="entidad" value="<?php echo $entidad ?>">
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
                                    <button id="{$item["id"]}" class="dropdown-item btn btn-danger eliminar-dato">Eliminar</button>
                                    <button class="dropdown-item btn btn-primary modificar-dato">Modificar</button>';

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
                            <button id="btnEliminar@<?php echo $item["id"]?>" class="dropdown-item btn btn-danger eliminar-dato">Eliminar</button>
                            <button id="btnModificar@<?php echo $item["id"]?>" class="dropdown-item btn btn-primary modificar-dato">Modificar</button>
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

    $(document).ready(function(){
        $(".eliminar-dato").click(function(){
            let idBoton=$(this).attr("id");
            let id= idBoton.split("@")[1];

            let entidad=$("#entidad").val();

            $.ajax({
                url: "/TDG/AJAX/eliminarDato",
                type: "POST",
                data: {
                    "id": id,
                    "entidad": entidad
                },
                success: function(data){
                    if(data=="Todo Correcto"){
                        console.log("Eliminado con exito");
                    }else{
                        console.log("Error en la eliminación")
                    }
                }
            });
        });
        
        // Modal de Modificación

        $(".modificar-dato").click(function(){
            $("#creacion_modificar_dato").modal("show");
            let idBoton=$(this).attr("id");
            let id= idBoton.split("@")[1];

            let entidad=$("#entidad").val();

            $.ajax({
                url: "/TDG/AJAX/datosModificarDato",
                type: "POST",
                data: {
                    "id": id,
                    "entidad": entidad
                },
                success: function(data){
                    $.each(JSON.parse(data)["dato"], function(key, value) {
                        $('#' + key + 'Input').val(value);
                    });
                }
            });
        });

        $("#btn_modificar").click(function(){
            let entidad=$("#entidad").val();
            let datos = {};

            $('[id$="Input"]').each(function() { //Este selector me permite buscar en el atributo, en este caso id, cuyo sufijo coincida con el indicado, en este caso Input (Como si fueran expresiones regulares).
                let id = $(this).attr('id');
                let key = id.replace('Input', ''); 
                let valor = $(this).val();
                datos[key] = valor;
            });

            let formData=new FormData();
            formData.append("datos", JSON.stringify(datos));
            formData.append("entidad", entidad);

            $.ajax({
                url: "/TDG/AJAX/modificarDato",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(data){
                    console.log(data);
                },
                error: function(error){
                    console.log(error);
                }
            })
        });

        $("#btn_cerrar_modal").click(function(){
            $('[id$="Input"]').each(function() { //Este selector me permite buscar en el atributo, en este caso id, cuyo sufijo coincida con el indicado, en este caso Input (Como si fueran expresiones regulares).
                $(this).val("");
            });

            $("#creacion_modificar_dato").modal("hide");
        });
    });

</script>
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