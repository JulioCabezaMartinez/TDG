<?php 

namespace App\Controllers;

use App\Models\Venta;
use App\Models\Juego;
use App\Models\Genero;
use App\Models\Plataforma;

use App\Core\Validators;

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
    public function finalizacion_compra(){
        // Eliminar la seguridad de esa venta una vez hecha.
        unset($_SESSION["id_venta"]);

        if(empty($_SESSION["usuarioActivo"])){
            header("Location:/login");
            exit;
        }

        $id_producto=Validators::evitarInyeccion($_GET["producto"]);

        $productoBD=new Venta();
        $producto=$productoBD->getById($id_producto);
        include_once __DIR__. "/../Views/finalizacion_compra.php";
    }

    public function checkout(){
        if(empty($_SESSION["usuarioActivo"])){
            header("Location:/login");
            exit;
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