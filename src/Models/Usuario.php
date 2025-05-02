<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Core\Security;

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
    public function logIn($correo, $pass) {

    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * @return void
     */
    public function logOut() {

    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param string $correo Correo electrónico del usuario.
     * @param string $pass Contraseña del usuario.
     * @param string $nick Nombre de usuario o apodo.
     * @param string|null $imagen Ruta o URL de la imagen del usuario (opcional).
     * @return mixed Resultado de la operación de registro.
     */
    public function register($nombre, $apellido, $correo, $pass, $nick, $imagen = null) {
        // Validar el correo electrónico y la contraseña
        if (empty($correo) || empty($pass) || empty($nick)) {
            return "Faltan Datos"; // O lanzar una excepción
        }

        // Comprobar si el correo ya está registrado
        $usuarioExistente = $this->compruebaCampo('correo', $correo);
        if (!is_bool($usuarioExistente)) {
            return "El correo ya se encuentra registrado"; // O lanzar una excepción
        }

        // Crear un nuevo usuario
        $nuevoUsuario = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'password' => Security::encryptPass($pass),
            'nick' => $nick,
            'imagen' => $imagen,
        ];

        $this->create($nuevoUsuario);

        return "Exito"; // O el ID del nuevo usuario
    }

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