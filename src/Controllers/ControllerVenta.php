<?php 

namespace App\Controllers;

use App\Models\Venta;
use App\Models\Juego;
use App\Models\Genero;

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
        $ventaBD=new Venta();
        $lista_ventas=$ventaBD->getAll();

        include_once __DIR__. "/../Views/lista_ventas.php";
    }

    public function view_venta(){
        $id_venta = $_GET["id_venta"];

        $ventaBD= new Venta();
        $juegoBD = new Juego();
        $generoBD = new Genero();

        $venta=$ventaBD->getById($id_venta);
        $juego=$juegoBD->getById($venta["id_juego"]);
        $generos=$generoBD->getGenerosJuegoById($juego["id"]);

        include_once __DIR__. "/../Views/venta.php";
    }

    public function detalles_Compra(){

        include_once __DIR__. "../Views/detalles_compra.php";
    }

    public function checkout(){
        $id_venta=$_GET["id"] ?? null;
        $_SESSION["id_venta"]=$id_venta;
        $venta=$this->venta->getById($id_venta);

        
        if($venta["Estado_Venta"]!="Disponible"){
            unset($_SESSION["id_venta"]);
            header("Location: /TDG/ventas");
        }else{

            include_once __DIR__. "/../Views/checkout.php";
        }
    }
}