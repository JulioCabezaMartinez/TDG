<?php

namespace App\Core;

class Validators{

    private static $expresiones_regulares=[
        "correo"=>'/^[\w.-]+@[\w.-]+\.\w+$/',
        "password_completa" => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?:{}|<>]).{8,}$/',
    ];

    public static function validarCamposExp($correo, $password){
        $correoValido=false;
        $passwordValida=false;

        if(preg_match(self::$expresiones_regulares["correo"], $correo)==1){
            $correoValido=true;
        }

        if(preg_match(self::$expresiones_regulares["password"], $password)==1){
            $passwordValida=true;
        }

        if($correoValido && $passwordValida){
            return true;
        }else{
            return false;
        }
    }

    public static function evitarInyeccion($campo){
        $resultado=htmlspecialchars($campo, ENT_QUOTES, 'UTF-8');

        return $resultado;
    }
}