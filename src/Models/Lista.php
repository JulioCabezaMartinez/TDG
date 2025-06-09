<?php

namespace App\Models;
use App\Core\EmptyModel;
use PDO;
use PDOException;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de listas.
 */
class Lista extends EmptyModel {
    /**
     * Constructor de la clase Lista.
     * Configura la tabla asociada al modelo.
     */
    public function __construct() {
        parent::__construct('listas', 'id');
    }

    /**
     * Asocia una lista a un usuario insertando en la tabla usuarios_listas.
     *
     * @param int $id_usuario ID del usuario.
     * @param int $id_lista ID de la lista.
     * @return void
     */
    private function asociaListasUsuario($id_usuario, $id_lista): void {
       try {
            $query = "INSERT INTO usuarios_listas (id_usuario, id_lista) VALUES (:id_usuario, :id_lista)";
            $params = [':id_usuario' => $id_usuario, ':id_lista' => $id_lista];
            $this->query($query, $params);
        } catch (PDOException $e) {
            error_log("Error al asociar lista a usuario: " . $e->getMessage());
        }
    }

    /**
     * Crea listas básicas para un usuario dado y las asocia con dicho usuario.
     *
     * Las listas creadas son:
     *  - Wish-{nick_usuario}
     *  - Backlog-{nick_usuario}
     *  - Completed-{nick_usuario}
     *  - Playing-{nick_usuario}
     *
     * @param string $nick_usuario Nickname o nombre del usuario.
     * @param int $id_usuario ID del usuario.
     * @return void
     */
    public function creaListasBasicas($nick_usuario, $id_usuario): void {
        try {
            $query = "INSERT INTO listas (id_tipo, nombre) VALUES (:id_tipo, :nombre)";
            $params = [
                [':id_tipo' => 1, ':nombre' => 'Wish-'.$nick_usuario],
                [':id_tipo' => 2, ':nombre' => 'Backlog-'.$nick_usuario],
                [':id_tipo' => 3, ':nombre' => 'Completed-'.$nick_usuario],
                [':id_tipo' => 4, ':nombre' => 'Playing-'.$nick_usuario]
            ];

            foreach ($params as $param) {
                $this->query($query, $param);
                $this->asociaListasUsuario($id_usuario, $this->db->lastInsertId());
            }
        } catch (PDOException $e) {
            error_log("Error al crear listas básicas: " . $e->getMessage());
        }
    }

    /**
     * Elimina las listas básicas asociadas a un usuario determinado.
     *
     * @param int $id_usuario ID del usuario.
     * @return bool Resultado de la ejecución de la sentencia SQL.
     */
    public function eliminarListasBasicas($id_usuario){
        $sql="DELETE FROM listas WHERE id IN (SELECT id_lista FROM usuarios_listas WHERE id_usuario=:id_usuario);";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Agrega un juego a una lista específica.
     *
     * @param int $id_Juego ID del juego a agregar.
     * @param int $id_Lista ID de la lista donde se agregará el juego.
     * @return void
     */
    public function addJuegoToLista($id_Juego, $lista, $id_user): bool|string{
        try {
            $nombre_lista = match($lista){
                'wish' => 'wishlist',
                'back' => 'backlog',
                'comp' => 'completed',
                'play' => 'playing',
                default => null
            };

            if ($nombre_lista) {
                $id_tipo_lista = $this->getIdTipoLista($nombre_lista);
                $id_lista = $this->getIdLista($id_user, $id_tipo_lista);

                if ($id_lista) {
                    $this->insertJuegoLista($id_Juego, $id_lista);
                    return true;
                } else {
                    return "idLista=null";
                }
            } else {
                return "nombreLista=null";
            }
        } catch (PDOException $e) {
            error_log("Error al agregar juego a lista: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un juego de la lista correspondiente de un usuario.
     *
     * @param int $id_Juego ID del juego a eliminar.
     * @param string $lista Nombre corto de la lista ('wish', 'back', 'comp', 'play').
     * @param int $id_user ID del usuario.
     * @return bool|string Retorna true si la eliminación fue exitosa,
     *                     "idLista=null" si no se encontró la lista,
     *                     "nombreLista=null" si el nombre de lista no es válido,
     *                     false si hubo un error en la base de datos.
     */
    public function deleteJuegoOfLista($id_Juego, $lista, $id_user): bool|string{
        try {
            $nombre_lista = match($lista){
                'wish' => 'wishlist',
                'back' => 'backlog',
                'comp' => 'completed',
                'play' => 'playing',
                default => null
            };

            if ($nombre_lista) {
                $id_tipo_lista = $this->getIdTipoLista($nombre_lista);
                $id_lista = $this->getIdLista($id_user, $id_tipo_lista);

                if ($id_lista) {
                    $this->deleteJuegoLista($id_Juego, $id_lista);
                    return true;
                } else {
                    return "idLista=null";
                }
            } else {
                return "nombreLista=null";
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar juego de lista: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el ID del tipo de lista a partir del nombre.
     *
     * @param string $nombre_lista Nombre de la lista (ej. 'wishlist', 'backlog', etc.).
     * @return int ID del tipo de lista, o 0 si no se encontró o hubo error.
     */
    private function getIdTipoLista($nombre_lista): int {
       try {
            $query = "SELECT id FROM listas_tipo WHERE nombre = :nombre_lista";
            $params = [':nombre_lista' => $nombre_lista];
            $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
            return $result[0]['id'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error al obtener id_tipo de lista: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene el ID de la lista asociada a un usuario y tipo de lista.
     *
     * @param int $id_user ID del usuario.
     * @param int $id_tipo_lista ID del tipo de lista.
     * @return int ID de la lista o 0 si no existe o hubo error.
     */
    private function getIdLista($id_user, $id_tipo_lista): int {
        try {
            $query = "SELECT l.id FROM usuarios_listas ul JOIN listas l ON ul.id_lista = l.id
                    WHERE ul.id_usuario = :id_usuario AND l.id_tipo = :id_tipo_lista";
            $params = [':id_usuario' => $id_user, ':id_tipo_lista' => $id_tipo_lista];
            $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
            return $result[0]['id'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error al obtener id de lista: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Inserta un juego en una lista específica.
     *
     * @param int $id_Juego ID del juego a insertar.
     * @param int $id_Lista ID de la lista donde se insertará el juego.
     * @return void
     */
    private function insertJuegoLista($id_Juego, $id_Lista): void {
        try {
            $query = "INSERT INTO juegos_lista (id_Juego, id_Lista) VALUES (:id_Juego, :id_Lista)";
            $params = [':id_Juego' => $id_Juego, ':id_Lista' => $id_Lista];
            $this->query($query, $params);
        } catch (PDOException $e) {
            error_log("Error al insertar juego en lista: " . $e->getMessage());
        }
    }

    /**
     * Elimina un juego de una lista específica.
     *
     * @param int $id_Juego ID del juego a eliminar.
     * @param int $id_Lista ID de la lista de la que se eliminará el juego.
     * @return void
     */
    private function deleteJuegoLista($id_Juego, $id_Lista): void {
        try {
            $query = "DELETE FROM juegos_lista WHERE id_juego=:id_Juego AND id_lista=:id_Lista";
            $params = [':id_Juego' => $id_Juego, ':id_Lista' => $id_Lista];
            $this->query($query, $params);
        } catch (PDOException $e) {
            error_log("Error al eliminar juego de lista: " . $e->getMessage());
        }
    }

    /**
     * Obtiene el tipo de lista dado su ID.
     *
     * @param int $id_Lista ID de la lista.
     * @return string El tipo de la lista o cadena vacía si no se encuentra.
     */
    public function getTipoLista($id_Lista) {
        try {
            $query = "SELECT id_tipo FROM listas WHERE id = :id_Lista";
            $params = [':id_Lista' => $id_Lista];
            $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
            return $result[0]['id_tipo'] ?? "";
        } catch (PDOException $e) {
            error_log("Error al obtener tipo de lista: " . $e->getMessage());
            return "";
        }
    }

    /**
     * Comprueba en qué listas está un juego dado.
     *
     * @param int $id_Juego ID del juego.
     * @param array $listas Array de listas donde buscar, cada una con clave 'id'.
     * @return array IDs de las listas donde el juego está presente.
     */
    public function compruebaJuegoLista($id_Juego, $listas) {
        try {
            $return = [];
            foreach ($listas as $lista) {
                $query = "SELECT * FROM juegos_lista WHERE id_Juego = :id_Juego AND id_Lista = :id_Lista";
                $params = [':id_Juego' => $id_Juego, ':id_Lista' => $lista['id']];
                $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $juego_lista) {
                    array_push($return, $juego_lista['id_Lista']);
                }
            }
            return $return;
        } catch (PDOException $e) {
            error_log("Error al comprobar juego en listas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene todas las listas asociadas a un usuario.
     *
     * @param int $id_usuario ID del usuario.
     * @return array Array con IDs de las listas.
     */
    public function getListasUsuario($id_usuario): array{
        try {
            $query = "SELECT l.id FROM listas l JOIN usuarios_listas ul ON l.id = ul.id_lista WHERE ul.id_usuario = :id_usuario";
            $params = [':id_usuario' => $id_usuario];
            return $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener listas del usuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los juegos de una lista específica de un usuario, con paginación.
     *
     * @param int $id_usuario ID del usuario.
     * @param string $tipo_lista Tipo de lista ('wishlist', 'completed', 'playing', 'backlog').
     * @param int $inicio Índice inicial para paginación.
     * @param int $limite Cantidad máxima de juegos a obtener.
     * @return array Lista de juegos (arrays asociativos).
     */
    public function getUserLists($id_usuario, $tipo_lista, $inicio, $limite) {
        try {
            $juegoDB = new Juego();
            $list = [];

            $id_tipo_lista = match($tipo_lista) {
                'wishlist' => 1,
                'completed' => 2,
                'playing'   => 3,
                'backlog'   => 4
            };

            // Obtener la id de la lista del usuario con ese tipo
            $sql = "
                SELECT l.id
                FROM usuarios_listas ul
                INNER JOIN listas l ON ul.id_lista = l.id
                WHERE ul.id_usuario = :id_usuario AND l.id_tipo = :id_tipo_lista
                LIMIT 1
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_lista', $id_tipo_lista, PDO::PARAM_INT);
            $stmt->execute();

            $lista = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lista) {
                return []; // El usuario no tiene lista de ese tipo
            }

            // Obtener juegos de esa lista con paginación
            $sql_juegos = "SELECT id_Juego FROM juegos_lista WHERE id_lista = :id_lista LIMIT :inicio, :limite";
            $stmt_juegos = $this->db->prepare($sql_juegos);
            $stmt_juegos->bindParam(':id_lista', $lista["id"], PDO::PARAM_INT);
            $stmt_juegos->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt_juegos->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt_juegos->execute();

            $juegos = $stmt_juegos->fetchAll(PDO::FETCH_ASSOC);

            foreach ($juegos as $juego) {
                $list[] = $juegoDB->getById($juego['id_Juego']);
            }

            return $list;

        } catch (PDOException $e) {
            error_log("Error al obtener juegos del usuario: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Obtiene el número total de juegos en una lista de un usuario.
     *
     * @param int $id_usuario ID del usuario.
     * @param string $tipo_lista Tipo de lista ('wishlist', 'completed', 'playing', 'backlog').
     * @return int Cantidad total de juegos en la lista.
     */
   public function getCountListasUsuario($id_usuario, $tipo_lista): int {
        try {
            $id_tipo_lista = match($tipo_lista){
                'wishlist' => 1,
                'completed' => 2,
                'playing'   => 3,
                'backlog'   => 4
            };

            // Obtener todas las listas del usuario con su tipo
            $sql = "
                SELECT l.id
                FROM usuarios_listas ul
                INNER JOIN listas l ON ul.id_lista = l.id
                WHERE ul.id_usuario = :id_usuario AND l.id_tipo = :id_tipo_lista
                LIMIT 1
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_lista', $id_tipo_lista, PDO::PARAM_INT);
            $stmt->execute();

            $lista = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lista) {
                return 0; // El usuario no tiene lista de este tipo
            }

            // Contar juegos en esa lista
            $sql_juegos = "SELECT COUNT(*) as total FROM juegos_lista WHERE id_lista = :id_lista";
            $stmt_juegos = $this->db->prepare($sql_juegos);
            $stmt_juegos->bindParam(':id_lista', $lista["id"], PDO::PARAM_INT);
            $stmt_juegos->execute();

            return $stmt_juegos->fetch(PDO::FETCH_ASSOC)["total"];

        } catch (PDOException $e) {
            error_log("Error al contar juegos en la lista del usuario: " . $e->getMessage());
            return 0;
        }
    }

}
?>