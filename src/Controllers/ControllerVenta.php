<?php 

namespace App\Controllers;

use App\Models\Venta;

/**
 * Controlador para gestionar las operaciones relacionadas con el modelo Venta.
 */
class ControllerVenta {
    /**
     * @var Venta Instancia del modelo Venta.
     */
    private $venta;

    /**
     * Constructor de la clase ControllerVenta.
     * Inicializa una nueva instancia del modelo Venta.
     */
    public function __construct() {
        $this->venta = new Venta();
    }

    /**
     * Obtiene todas las ventas.
     *
     * @return void
     */
    public function getAllVentas(): void {
        $this->venta->getAll();
        require_once '../src/Views/ventas.php';
    }

    /**
     * Obtiene una venta por su ID.
     *
     * @param int $id ID de la venta a obtener.
     * @return void
     */
    public function getVentaById($id): void {
        $this->venta->getById($id);
        require_once '../src/Views/ventas.php';
    }

    /**
     * Actualiza una venta existente.
     *
     * @param int $id ID de la venta a actualizar.
     * @param array $data Datos actualizados de la venta.
     * @return void
     */
    public function updateVenta($id, $data): void {
        $this->venta->update(id: $id, data: $data);
        require_once '../src/Views/ventas.php';
    }

    /**
     * Elimina una venta por su ID.
     *
     * @param int $id ID de la venta a eliminar.
     * @return void
     */
    public function deleteVenta($id): void {
        $this->venta->delete($id);
        require_once '../src/Views/ventas.php';
    }

    public function lista_ventas(){
        $ventaBD=new Venta();
        $lista_ventas=$ventaBD->getAll();

        require_once __DIR__. "/../Views/lista_ventas.php";
    }

    public function view_venta(){
        require_once __DIR__. "/../Views/venta.php";
    }

    public function detalles_Compra(){


        require_once __DIR__. "../Views/detalles_compra.php";
    }

    public function checkout(){
        $id_venta=$_GET["id"] ?? null;
        $_SESSION["id_venta"]=$id_venta;
        $venta=$this->venta->getById($id_venta);

        
        if($venta["Estado_Venta"]!="Disponible"){
            unset($_SESSION["id_venta"]);
            header("Location: /TDG/ventas");
        }else{

            require_once __DIR__. "/../Views/checkout.php";
        }
    }
}