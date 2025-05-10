<nav class="pag">
    <ul class="pagination">
        <?php

        $numero_inicio=1;

        if(($pagina-4)>1){ // 4 es para tener 4 páginas a la izquierda.
            $numero_inicio=$pagina-4;
        }

        $numero_fin=$numero_inicio + 8; // 9 páginas en total. 4 izq + 4 der + 1 central.

        if($numero_fin > $total_paginas){ // 4 es para tener 4 páginas a la izquierda.
            $numero_fin=$total_paginas;
        }

        for ($i = $numero_inicio; $i <= $numero_fin; $i++) {
            if ($i == $pagina) {
                echo "<li class='page-item active'><a class='page-link' href='#' onClick='paginacion({$i})'>{$i}</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='#' onClick='paginacion({$i})'>{$i}</a></li>"; //Pasar el número de página por GET. En Ajax no hará falta.
            }
        }
        ?>
    </ul>
</nav>