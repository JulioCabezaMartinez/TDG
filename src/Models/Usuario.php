<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Core\Security;

use PDO;

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
        $usuario=$this->query("Select * from usuarios where correo='{$correo}';")->fetchAll(PDO::FETCH_ASSOC);

        if(password_verify($pass, $usuario[0]["Password"])){
            return $usuario[0];
        }else{
            return false;
        }
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
    public function register($nombre, $apellido, $correo, $pass, $nick, $direccion) {

        // Comprobar si el correo ya está registrado
        $usuarioExistente = $this->compruebaCampo('correo', $correo);
        if (!is_bool($usuarioExistente)) {
            return "Correo";
        }

        // Crear un nuevo usuario
        $nuevoUsuario = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'password' => Security::encryptPass($pass),
            'nick' => $nick,
            'direccion' => $direccion,
            'imagen_usuario' => "default-user.png", // Ruta de la imagen por defecto
        ];

        return $this->create($nuevoUsuario);
    }

    /**
     * Cambia la contraseña de un usuario.
     *
     * @param string $correo Correo electrónico del usuario.
     * @param string $AntiguaPass Contraseña actual del usuario.
     * @param string $nuevaPass Nueva contraseña del usuario.
     * @return mixed Resultado de la operación de cambio de contraseña.
     */
    public function cambiarPass($correo, $AntiguaPass, $nuevaPass) {
        
    }

    public function buscarAdmin($textoBusqueda, $inicio, $limit){

        $sql = "SELECT * FROM {$this->table} WHERE Nombre LIKE :textoBusqueda LIMIT {$inicio}, {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function buscarAdminCount($textoBusqueda){

        $sql = "SELECT * FROM {$this->table} WHERE Nombre LIKE :textoBusqueda";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>