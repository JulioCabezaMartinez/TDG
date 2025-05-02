<?php
// Declaración del namespace
namespace App\Core;

/**
 * Clase de seguridad con métodos estáticos
 */
class Security
{

    // Constructor privado para impedir instanciar la clase
    private function __construct() {}

    /**
     * Método que comprueba si el usuario está logeado
     *
     * @return bool Si el usuario está logeado
     */
    public static function isUserLogged(): bool
    {
        return isset($_SESSION['userId']);
    }

    /**
     * Método que cierra la sesión y redirige al login
     *
     * @return void
     */
    public static function closeSession(): bool
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit;
    }

    /**
     * Método que encripta la contraseña pasada por parámetro
     *
     * @param string $pass La contraseña a encriptar
     * @return string La contraseña encriptada
     */
    public static function encryptPass(string $pass): string
    {
        return password_hash($pass, PASSWORD_DEFAULT);
    }
}
