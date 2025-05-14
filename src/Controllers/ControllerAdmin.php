<?php

namespace App\Controllers;

use App\Models\Juego;
use App\Models\Usuario;
use App\Models\Review;
use App\Models\Venta;

class ControllerAdmin{

    private $entidades=["usuarios", "juegos", "reviews", "productos", "post_vendidos"];

    public function tabla(){
        $entidad=$_GET["tabla"];
        $tablaCorrecta=false;

        foreach($this->entidades as $tabla){
            if($tabla==$entidad){
                $tablaCorrecta=true;
            }
        }

        if($tablaCorrecta){

            switch($entidad){
                case "usuarios":
                    $usuarioDB=new Usuario();
                    $datos=$usuarioDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$usuarioDB->getAll();
                    break;
                case "juegos":
                    $juegoDB=new Juego();
                    $datos=$juegoDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$juegoDB->getAll();
                    break;
                case "reviews":
                    $reviewDB=new Review();
                    $datos=$reviewDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$reviewDB->getAll();
                    break;
                case "productos":
                    $ventaDB=new Venta();
                    $datos=$ventaDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$ventaDB->getAll();
                    break;
                case "post_vendidos":
                    $ventaDB=new Venta();
                    $datos=$ventaDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$ventaDB->muestraAllVentas();
                    break;
            }

            include __DIR__. '/../Views/tablaAdmin.php';
        }else{
            header("Location: /TDG/");
        }

        
    }
}