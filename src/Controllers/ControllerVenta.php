<?php 

namespace App\Controllers;

use App\Models\Venta;
use App\Models\Juego;
use App\Models\Genero;
use App\Models\Plataforma;

use App\Core\Validators;
use App\Core\Security;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Venta.
 */
class ControllerVenta {
    /**
     * @var Venta Instancia del modelo Venta.
     */
    private $venta;

    public function __construct(){
        $this->venta=new Venta();
    }

    /**
     * Muestra la lista general de ventas disponibles.
     *
     * Recupera las consolas ordenadas alfabéticamente, las columnas de la tabla `ventas`
     * y la lista de juegos ordenados. Posteriormente, se carga la vista `lista_ventas.php`.
     *
     * @return void
     */
    public function lista_ventas(){

        $plataformaDB=new Plataforma();
        $ventaBD = new Venta();
        $juegoBD = new Juego();

        $consolas=$plataformaDB->getAllOrderByAlfabet();
        $datos = $ventaBD->listaColumnas();

        $columnas = [];
        foreach ($datos as $dato) {
            array_push($columnas, $dato["Field"]);
        }
        
        $juegos = $juegoBD->getAllOrderByAlfabet();

        include_once __DIR__. "/../Views/lista_ventas.php";
    }

    /**
     * Muestra los detalles de una venta específica seleccionada.
     *
     * Obtiene la información completa de la venta, el juego asociado, sus géneros,
     * consolas compatibles y otras ventas disponibles. También recupera las columnas
     * de la tabla `ventas`. Finalmente, se carga la vista `venta.php`.
     *
     * @return void
     */
    public function view_venta(){
        $id_venta = $_GET["id"];

        $ventaBD= new Venta();
        $juegoBD = new Juego();
        $generoBD = new Genero();
        $plataformaDB = new Plataforma();

        $venta=$ventaBD->getById($id_venta);
        $juego=$juegoBD->getById($venta["id_juego"]);
        $generos=$generoBD->getGenerosJuegoById($juego["id"]);

        $consolas=$plataformaDB->getAllOrderByAlfabet();
        $juegos = $juegoBD->getAllOrderByAlfabet();
        // Datos para las columnas de la tabla de ventas
        $datos = $ventaBD->listaColumnas();
        $columnas = [];
        foreach ($datos as $dato) {
            array_push($columnas, $dato["Field"]);
        }

        include_once __DIR__. "/../Views/venta.php";
    }

    /**
     * Muestra la vista de finalización de compra tras completar una venta.
     *
     * Elimina el identificador temporal de la venta de la sesión.
     * Verifica que exista sesión activa, luego obtiene y muestra la información
     * del producto comprado. Carga la vista `finalizacion_compra.php`.
     *
     * @return void
     */
    public function finalizacion_compra(){
        // Eliminar la seguridad de esa venta una vez hecha.
        unset($_SESSION["id_venta"]);

        if(empty($_SESSION)) {
            Security::closeSession();
        }

        $id_producto=Validators::evitarInyeccion($_GET["producto"]);

        $productoBD=new Venta();
        $producto=$productoBD->getById($id_producto);
        include_once __DIR__. "/../Views/finalizacion_compra.php";
    }

    /**
     * Procesa y muestra el formulario de checkout para una venta específica.
     *
     * Verifica que el usuario esté logueado. Establece el ID de la venta en sesión,
     * luego obtiene sus datos. Si la venta no es válida o no está disponible,
     * redirige al listado de ventas. En caso contrario, carga la vista `checkout.php`
     * con el precio de gestión correspondiente.
     *
     * @return void
     */
    public function checkout(){
        if(empty($_SESSION)) {
            Security::closeSession();
        }
        $id_venta=$_GET["id"] ?? null;
        $_SESSION["id_venta"]=$id_venta;
        $venta=$this->venta->getById($id_venta);

        
        if($venta["id"]==-1){
            $precio_gestion = 0; // Coste de gestión
            include_once __DIR__. "/../Views/checkout.php";
        }else if($venta["Estado_Venta"]!="Disponible"){
            unset($_SESSION["id_venta"]);
            header("Location: /ventas");
        }else{
            $precio_gestion = 2.99; // Coste de gestión
            include_once __DIR__. "/../Views/checkout.php";
        }
    }
}