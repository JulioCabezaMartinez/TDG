<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Core\Security;
use App\Interfaces\BusquedaAdmin;

use PDO;
use PDOException;

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
        try {
            $usuario = $this->query("SELECT * FROM usuarios WHERE correo = '{$correo}';")->fetchAll(PDO::FETCH_ASSOC);
            if (empty($usuario)) {
                return false; // Usuario no encontrado
            }
            if (password_verify($pass, $usuario[0]["Password"])) {
                return $usuario[0];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en logIn: " . $e->getMessage());
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
    public function register($nombre, $apellido, $correo, $pass, $nick, $direccion, $imagen="default-user.jpg") {
        try {
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
        } catch (PDOException $e) {
            error_log("Error en register: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cambia la contraseña de un usuario.
     *
     * @param int $id_usuario ID del usuario.
     * @param string $pass Nueva contraseña en texto plano.
     * @return int Cantidad de filas afectadas (1 si se actualizó, 0 si no).
     */
    public function cambiarPass($id_usuario, $pass) {
        try {
            $passHash = Security::encryptPass($pass);
            $sql = "UPDATE usuarios SET Password = :pass WHERE id = :id_usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pass', $passHash, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error en cambiarPass: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Busca registros en modo administrador con paginación y filtrado por nombre.
     *
     * @param string $textoBusqueda Texto para buscar (debe incluir % para LIKE).
     * @param int $inicio Índice inicial para la paginación.
     * @param int $limit Número máximo de resultados.
     * @return array Lista de registros que coinciden con la búsqueda.
     */
    public function buscarAdmin($textoBusqueda, $inicio, $limit){

        try {
            $sql = "SELECT * FROM {$this->table} WHERE Nombre LIKE :textoBusqueda LIMIT {$inicio}, {$limit}";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en buscarAdmin: " . $e->getMessage());
            return [];
        }

    }

    /**
     * Cuenta la cantidad de registros que coinciden con la búsqueda en modo administrador.
     *
     * @param string $textoBusqueda Texto para buscar (debe incluir % para LIKE).
     * @return int Cantidad total de registros que coinciden.
     */
    public function buscarAdminCount($textoBusqueda){

        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE Nombre LIKE :textoBusqueda";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en buscarAdminCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Otorga el estado Premium a un usuario.
     *
     * @param int $id_usuario ID del usuario.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function conseguirPremium($id_usuario){
        try {
            return $this->query("UPDATE usuarios SET Premium = 1 WHERE id = {$id_usuario};");
        } catch (PDOException $e) {
            error_log("Error en conseguirPremium: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Añade una imagen al sistema, validando extensión y tamaño, y la guarda con un nombre único.
     *
     * @param array $imagen Array con los datos de la imagen (como $_FILES['imagen']).
     * @return string Mensaje de error o el nombre único generado para la imagen.
     */
    public function addImagen($imagen){
        $Nombreimagen = uniqid();

        $extension = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];

        if ($imagen['size'] > (12 * 1024 * 1204)) { //Que el tamaño no sea mayor de 12 mb

            return "Imagen demasiado pesada";
        } elseif (!in_array($extension, $extensionesPermitidas)) {

            return "El archivo tiene un tipo no permitido";
        } else {

            $filename = $Nombreimagen . ".jpg";
            $tempName = $imagen['tmp_name'];
            if (isset($filename)) {
                if (!empty('$filename')) {
                    $location = __DIR__ . "/../../public/IMG/Users-img/" . $filename;
                    move_uploaded_file($tempName, $location);
                }
            }
        }

        return $Nombreimagen;
    }

    /**
     * Elimina una imagen del sistema dado su ruta.
     *
     * @param string $rutaImagen Ruta completa de la imagen a eliminar.
     * @return bool True si la imagen se eliminó correctamente, false si no existía o hubo error.
     */
    public function eliminarImagen($rutaImagen){
        try {
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Error en eliminarImagen: " . $e->getMessage());
            return false;
        }
    }
}
?>