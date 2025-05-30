<?php

namespace App\Models;
use App\Core\EmptyModel;
use PDO;

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
    private function asociaListasUsuario($id_usuario, $id_lista): void {
        $query = "INSERT INTO usuarios_listas (id_usuario, id_lista) VALUES (:id_usuario, :id_lista)";
        $params = [':id_usuario' => $id_usuario, ':id_lista' => $id_lista];
        $this->query($query, $params);
    }

    public function creaListasBasicas($nick_usuario, $id_usuario): void {
        $query = "INSERT INTO listas (id_tipo, nombre) VALUES (:id_tipo, :nombre)";
        $params = [
            [':id_tipo' => 1, ':nombre' => 'Wish-'.$nick_usuario],
            [':id_tipo' => 2, ':nombre' => 'Completed-'.$nick_usuario],
            [':id_tipo' => 3, ':nombre' => 'Playing-'.$nick_usuario],
            [':id_tipo' => 4, ':nombre' => 'Backlog-'.$nick_usuario]
        ];

        foreach ($params as $param) {
            $this->query($query, $param);
        
            // Asociar la lista recién creada al usuario.
            $this->asociaListasUsuario($id_usuario, $this->db->lastInsertId()); // Asocia las listas creadas al usuario.
        }
    }

    /**
     * Agrega un juego a una lista específica.
     *
     * @param int $id_Juego ID del juego a agregar.
     * @param int $id_Lista ID de la lista donde se agregará el juego.
     * @return void
     */
    public function addJuegoToLista($id_Juego, $lista, $id_user): bool|string{
        $nombre_lista= match($lista){
            'wish' => 'wishlist',
            'back' => 'backlog',
            'comp' => 'completed',
            'play' => 'playing',
            default => null
        };

        if($nombre_lista){
            $id_tipo_lista = $this->getIdTipoLista($nombre_lista);
            $id_lista = $this->getIdLista($id_user, $id_tipo_lista);

            if($id_lista){
                
                $this->insertJuegoLista($id_Juego, $id_lista); // Agregar el juego a la lista.
                return true;
            } else {
                return "idLista=null"; // La lista no existe para el usuario.
            }
        }else{
            return "nombreLista=null"; // El nombre de la lista no es válido.
        }
    }

    public function deleteJuegoOfLista($id_Juego, $lista, $id_user): bool|string{
        $nombre_lista= match($lista){
            'wish' => 'wishlist',
            'back' => 'backlog',
            'comp' => 'completed',
            'play' => 'playing',
            default => null
        };

        if($nombre_lista){
            $id_tipo_lista = $this->getIdTipoLista($nombre_lista);
            $id_lista = $this->getIdLista($id_user, $id_tipo_lista);

            if($id_lista){
                // Preguntar a Ruben si crear otro modelo para la lista de juegos en tablas o usar el metodo privado que se ha creado.
                
                // $this->create(array(
                //     'id_Juego' => $id_Juego,
                //     'id_Lista' => $id_lista
                // )); 
                $this->deleteJuegoLista($id_Juego, $id_lista); // Agregar el juego a la lista.
                return true;
            } else {
                return "idLista=null"; // La lista no existe para el usuario.
            }
        }else{
            return "nombreLista=null"; // El nombre de la lista no es válido.
        }
    }

    private function getIdTipoLista($nombre_lista): int {
        $query = "SELECT id FROM listas_tipo WHERE nombre = :nombre_lista";
        $params = [':nombre_lista' => $nombre_lista];
        $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['id'] ?? 0; // Retorna 0 si no se encuentra el tipo de lista.
    }

    private function getIdLista($id_user, $id_tipo_lista): int {
        $query = "SELECT l.id FROM usuarios_listas ul JOIN listas l
        ON ul.id_lista = l.id
        WHERE ul.id_usuario = :id_usuario AND l.id_tipo = :id_tipo_lista";

        $params = [':id_usuario' => $id_user, ':id_tipo_lista' => $id_tipo_lista];
        $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['id'] ?? 0; // Retorna 0 si no se encuentra la lista.
    }

    private function insertJuegoLista($id_Juego, $id_Lista): void {
        $query = "INSERT INTO juegos_lista (id_Juego, id_Lista) VALUES (:id_Juego, :id_Lista)";
        $params = [':id_Juego' => $id_Juego, ':id_Lista' => $id_Lista];
        $this->query($query, $params);
    }

    /**
     * Elimina un juego de una lista específica.
     *
     * @param int $id_Juego ID del juego a eliminar.
     * @param int $id_Lista ID de la lista de la que se eliminará el juego.
     * @return void
     */
    private function deleteJuegoLista($id_Juego, $id_Lista): void {
        $query = "DELETE FROM juegos_lista WHERE id_juego=:id_Juego AND id_lista=:id_Lista;";
        $params = [':id_Juego' => $id_Juego, ':id_Lista' => $id_Lista];
        $this->query($query, $params);
    }

    public function getTipoLista($id_Lista) {
        $query = "SELECT id_tipo FROM listas WHERE id = :id_Lista";
        $params = [':id_Lista' => $id_Lista];
        $result=$this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);

        return $result[0]['id_tipo'] ?? "";
    }

    public function compruebaJuegoLista($id_Juego, $listas) {
        $return = [];
        foreach ($listas as $lista) {
            $query = "SELECT * FROM juegos_lista WHERE id_Juego = :id_Juego AND id_Lista = :id_Lista";
            $params = [':id_Juego' => $id_Juego, ':id_Lista' => $lista['id']];
            $result = $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $juego_lista) {
                array_push($return, $juego_lista['id_Lista']);
            }
        }
        return $return; // Retorna un array con los ids de las listas donde se encuentra el juego.
    }

    public function getListasUsuario($id_usuario): array
    {
        $query = "SELECT l.id FROM listas l JOIN usuarios_listas ul ON l.id = ul.id_lista WHERE ul.id_usuario = :id_usuario";
        $params = [':id_usuario' => $id_usuario];
        return $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLists($id_usuario, $tipo_lista, $inicio, $limite){
        $juegoDB= new Juego();
        $list = [];

        $id_tipo_lista = match($tipo_lista){
            'wishlist' => 1,
            'completed' => 2,
            'playing' => 3,
            'backlog' => 4
        };

        $sql_listas_usuario = "SELECT id_lista FROM usuarios_listas WHERE id_usuario = :id_usuario";
        $stmt_listas_usuario = $this->db->prepare($sql_listas_usuario);
        $stmt_listas_usuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_listas_usuario->execute();
        $listas_usuario = $stmt_listas_usuario->fetchAll(PDO::FETCH_ASSOC);


        $listas_whis = [];
        foreach ($listas_usuario as $lista) {
            $sql_listas = "SELECT id FROM listas WHERE id_tipo = {$id_tipo_lista} AND id = :id_lista";
            $stmt_listas = $this->db->prepare($sql_listas);
            $stmt_listas->bindParam(':id_lista', $lista['id_lista'], PDO::PARAM_INT);
            $stmt_listas->execute();

            $listas_whis[] = $stmt_listas->fetch(PDO::FETCH_ASSOC);
        }

        $posicion_array = match($tipo_lista){
            'wishlist' => 0,
            'backlog' => 1,
            'completed' => 2,
            'playing' => 3
        };

        // Obtener los juegos de la lista de deseos
        $sql_juegos = "SELECT id_Juego FROM juegos_lista WHERE id_lista = :id_lista LIMIT :inicio, :limite";
        $stmt_juegos = $this->db->prepare($sql_juegos);
        $stmt_juegos->bindParam(':id_lista', $listas_whis[$posicion_array]["id"], PDO::PARAM_INT);
        $stmt_juegos->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt_juegos->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt_juegos->execute();
        $juegos = $stmt_juegos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($juegos as $juego) {
            $list[] = $juegoDB->getById($juego['id_Juego']);
        }

        return $list;
    }

    public function getCountListasUsuario($id_usuario, $tipo_lista): int{

        $id_tipo_lista = match($tipo_lista){
            'wishlist' => 1,
            'completed' => 2,
            'playing' => 3,
            'backlog' => 4
        };

        $sql_listas_usuario = "SELECT id_lista FROM usuarios_listas WHERE id_usuario = :id_usuario";
        $stmt_listas_usuario = $this->db->prepare($sql_listas_usuario);
        $stmt_listas_usuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_listas_usuario->execute();
        $listas_usuario = $stmt_listas_usuario->fetchAll(PDO::FETCH_ASSOC);


        $listas_whis = [];
        foreach ($listas_usuario as $lista) {
            $sql_listas = "SELECT id FROM listas WHERE id_tipo = {$id_tipo_lista} AND id = :id_lista";
            $stmt_listas = $this->db->prepare($sql_listas);
            $stmt_listas->bindParam(':id_lista', $lista['id_lista'], PDO::PARAM_INT);
            $stmt_listas->execute();

            $listas_whis[] = $stmt_listas->fetch(PDO::FETCH_ASSOC);
        }

        $posicion_array = match($tipo_lista){
            'wishlist' => 0,
            'backlog' => 1,
            'completed' => 2,
            'playing' => 3
        };

        // Obtener los juegos de la lista de deseos
        $sql_juegos = "SELECT COUNT(*) as total FROM juegos_lista WHERE id_lista = :id_lista";
        $stmt_juegos = $this->db->prepare($sql_juegos);
        $stmt_juegos->bindParam(':id_lista', $listas_whis[$posicion_array]["id"], PDO::PARAM_INT);
        $stmt_juegos->execute();

        return $stmt_juegos->fetch(PDO::FETCH_ASSOC)["total"];
    }
}
?>