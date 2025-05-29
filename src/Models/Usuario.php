<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Core\Security;
use App\Interfaces\BusquedaAdmin;

use PDO;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de usuarios.
 */
class Usuario extends EmptyModel implements BusquedaAdmin {
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

        if(empty($usuario)){
            return false; // Usuario no encontrado
        }

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
    public function register($nombre, $apellido, $correo, $pass, $nick, $direccion, $imagen="default-user.png") {

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
            'imagen_usuario' => $imagen
        ];

        return $this->create($nuevoUsuario);
    }

    public function cambiarPass($id_usuario, $pass) {
        $passHash=password_hash($pass, PASSWORD_DEFAULT);
        $sql="UPDATE usuarios SET Password=:pass WHERE id=:id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pass', $passHash, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
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

    public function conseguirPremium($id_usuario){
        return $this->query("UPDATE usuarios SET Premium=1 WHERE id={$id_usuario};");
    }

    public function eliminarImagen($rutaImagen){
        unlink($rutaImagen);
    }
}
?>