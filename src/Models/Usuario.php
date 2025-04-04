<?php

namespace App\Models;

use App\Models\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de usuarios.
 */
class Usuario extends EmptyModel {
    /**
     * Constructor de la clase Usuario.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('usuarios', 'id');
    }

    /**
     * Inicia sesión de un usuario.
     *
     * @param string $correo Correo electrónico del usuario.
     * @param string $pass Contraseña del usuario.
     * @return mixed Resultado de la operación de inicio de sesión.
     */
    public function logIn($correo, $pass) {}

    /**
     * Cierra la sesión del usuario actual.
     *
     * @return void
     */
    public function logOut() {}

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param string $correo Correo electrónico del usuario.
     * @param string $pass Contraseña del usuario.
     * @param string $nick Nombre de usuario o apodo.
     * @param string|null $imagen Ruta o URL de la imagen del usuario (opcional).
     * @return mixed Resultado de la operación de registro.
     */
    public function register($correo, $pass, $nick, $imagen = null) {}

    /**
     * Cambia la contraseña de un usuario.
     *
     * @param string $correo Correo electrónico del usuario.
     * @param string $AntiguaPass Contraseña actual del usuario.
     * @param string $nuevaPass Nueva contraseña del usuario.
     * @return mixed Resultado de la operación de cambio de contraseña.
     */
    public function cambiarPass($correo, $AntiguaPass, $nuevaPass) {}
}
?>