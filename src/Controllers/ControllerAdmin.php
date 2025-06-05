<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Juego;
use App\Models\Usuario;
use App\Models\Review;
use App\Models\Venta;
use App\Models\Plataforma;
use App\Models\Genero;

class ControllerAdmin{

    private $entidades=["usuarios", "juegos", "reviews", "productos", "post_vendidos"];

    public function admin(){

        if(empty($_SESSION) || !$_SESSION["Admin"]) {
            Security::closeSession();
        }

        include_once __DIR__. '/../Views/panelAdmin.php';
    }

    public function tabla(){

        if(empty($_SESSION) || !$_SESSION["Admin"]) {
            Security::closeSession();
        }

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
                    $plataformaDB=new Plataforma();
                    $generoDB=new Genero();
                    $datos=$juegoDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $plataformas=$plataformaDB->getAllOrderByAlfabet();
                    $generos=$generoDB->getAllOrderByAlfabet();
                    $lista=$juegoDB->getAll();
                    break;
                case "reviews":
                    $usuarioDB=new Usuario();
                    $reviewDB=new Review();
                    $juegoDB=new Juego();
                    $datos=$reviewDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$reviewDB->getAll();
                    $usuarios=$usuarioDB->getAll();
                    $juegos=$juegoDB->getAllOrderByAlfabet();
                    break;
                case "productos":
                    $ventaDB=new Venta();
                    $usuarioDB=new Usuario();
                    $juegoDB=new Juego();
                    $plataformaDB=new Plataforma();
                    $datos=$ventaDB->listaColumnas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$ventaDB->getAll();
                    $usuarios=$usuarioDB->getAll();
                    $juegos=$juegoDB->getAllOrderByAlfabet();
                    $consolas=$plataformaDB->getAllOrderByAlfabet();
                    break;
                case "post_vendidos":
                    $ventaDB=new Venta();
                    $usuarioDB=new Usuario();
                    $datos=$ventaDB->muestraColumnasVentas();
                    $columnas=[];
                    foreach($datos as $dato){
                        array_push($columnas, $dato["Field"]);
                    }
                    $lista=$ventaDB->muestraAllVentas();
                    $usuarios=$usuarioDB->getAll();
                    $productos=$ventaDB->getAll();
                    break;
            }

            include __DIR__. '/../Views/tablaAdmin.php';
        }else{
            header("Location: /");
        }

        
    }
}