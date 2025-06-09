<?php

namespace App\Models;

use App\Core\EmptyModel;
use App\Traits\BusquedaAlfa;
use App\Interfaces\BusquedaAdmin;


/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de juegos.
 */
class Juego extends EmptyModel implements BusquedaAdmin {


    use BusquedaAlfa;

    /**
     * Constructor de la clase Juego.
     * Configura la tabla y la clave primaria asociadas al modelo.
     */
    public function __construct() {
        parent::__construct('juegos', 'id');
    }

    /**
     * Obtiene el conteo de registros que cumplen con ciertos filtros.
     *
     * @param array $filtros Array asociativo con posibles filtros:
     *                       - 'nombre' (string): filtro por nombre con LIKE.
     *                       - 'fechaSalida' (string): filtro de fecha mínima de salida.
     *                       - 'fechaNextMonth' (string): filtro de fecha máxima de salida.
     *                       - 'calificacion' (float|int): filtro de calificación.
     * @return int Cantidad de registros que cumplen los filtros.
     * @throws \Exception Si ocurre un error en la consulta a la base de datos.
     */
    public function getCountFiltros($filtros){
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table}";
            $conditions = [];

            if (!empty($filtros)) {
                if (!empty($filtros['nombre'])) {
                    $conditions[] = "Nombre LIKE '{$filtros['nombre']}'";
                }

                if (!empty($filtros['fechaSalida'])) {
                    $conditions[] = "Anyo_salida > '{$filtros['fechaSalida']}' AND Anyo_salida < '{$filtros['fechaNextMonth']}'";
                }

                if (!empty($filtros['calificacion'])) {
                    $conditions[] = "calificacion > {$filtros['calificacion']} AND calificacion < {$filtros['calificacion']}+1";
                }

                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }
            }

            return parent::query($sql)->fetchColumn();
        } catch (\PDOException $e) {
            throw new \Exception("Error al contar juegos con filtros: " . $e->getMessage());
        }
    }

    /**
     * Obtiene los 10 juegos más recientes ordenados por fecha de salida descendente.
     *
     * @return array Lista de juegos en formato asociativo.
     */
    public function getNew(): array {
        return parent::query("SELECT * FROM {$this->table} ORDER BY Anyo_salida DESC LIMIT 10")->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una lista de juegos con paginación y filtros opcionales.
     *
     * @param int $inicio Índice inicial para la paginación.
     * @param int $limit Cantidad máxima de resultados a devolver.
     * @param array $filtros (Opcional) Array asociativo con filtros similares a getCountFiltros.
     * @return array Lista de juegos filtrados y paginados.
     * @throws \Exception Si ocurre un error en la consulta.
     */
    public function getListGames(int $inicio, int $limit, $filtros = []){
        try {
            $sql = "SELECT * FROM {$this->table}";
            $conditions = [];

            if (!empty($filtros)) {
                if (!empty($filtros['nombre'])) {
                    $conditions[] = "Nombre LIKE '{$filtros['nombre']}'";
                }

                if (!empty($filtros['fechaSalida'])) {
                    $conditions[] = "Anyo_salida > '{$filtros['fechaSalida']}' AND Anyo_salida < '{$filtros['fechaNextMonth']}'";
                }

                if (!empty($filtros['calificacion'])) {
                    $conditions[] = "calificacion > {$filtros['calificacion']} AND calificacion < {$filtros['calificacion']}+1";
                }

                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }
            }

            $sql .= " LIMIT {$inicio}, {$limit}";

            return parent::query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error al obtener la lista de juegos: " . $e->getMessage());
        }
    }

    /**
     * Busca juegos para administración filtrando por texto con paginación.
     *
     * @param string $textoBusqueda Texto para búsqueda en el campo Nombre (debe incluir comodines SQL).
     * @param int $inicio Índice inicial para la paginación.
     * @param int $limit Cantidad máxima de resultados.
     * @return array Lista de juegos encontrados.
     * @throws \Exception Si ocurre un error en la consulta.
     */
    public function buscarAdmin($textoBusqueda, $inicio, $limit){
        try {
            $sql = "SELECT * FROM {$this->table} WHERE Nombre LIKE :textoBusqueda LIMIT {$inicio}, {$limit}";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Error en búsqueda admin: " . $e->getMessage());
        }
    }

    /**
     * Cuenta la cantidad de resultados para búsqueda administrativa con filtro por texto.
     *
     * @param string $textoBusqueda Texto para búsqueda en el campo Nombre (debe incluir comodines SQL).
     * @return int Cantidad de resultados encontrados.
     * @throws \Exception Si ocurre un error en la consulta.
     */
    public function buscarAdminCount($textoBusqueda){
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE Nombre LIKE :textoBusqueda";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            throw new \Exception("Error al contar resultados admin: " . $e->getMessage());
        }
    }

    /**
     * Rellena la base de datos con juegos obtenidos de la API RAWG.
     *
     * @param int $countAPI Cantidad total de juegos obtenidos de la API para paginar.
     * @return void
     * 
     * Este método hace múltiples llamadas a la API para obtener juegos y sus detalles,
     * verifica si ya existen en la base de datos, y si no, los inserta junto con sus géneros y plataformas.
     */
    public function rellenarBD($countAPI){

        $pages=$countAPI/40; // Asegurarse de que countAPI es un entero.


        for($page=1; $page<=$pages; $page++){
            $url="https://api.rawg.io/api/games?key=".$_ENV["API_KEY"]."&page={$page}&page_size=40";
    
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json'
            ));
            $response = curl_exec($ch);
            curl_close($ch);
    
            // Decodificar la respuesta JSON
            $data = json_decode($response, true);
    
            foreach($data['results'] as $juego){
                // Comprobar si el juego ya existe en la base de datos
                $juego_bd = $this->getById($juego['id']);

                if($juego_bd!=null){ //En caso de que el juego sea diferente de null, significa que ya existe en la base de datos.

                    continue; // Si el juego ya existe, saltar al siguiente juego
                }

                // Obtener el ID del juego
                $id_juego = $juego['id'];

                // Obtener el nombre del juego
                $nombre = $juego['name'];

                // Obtener la fecha de lanzamiento
                if($juego['released'] == null){
                    $fecha_lanzamiento = 0; // Si no hay fecha de lanzamiento, asignar 0
                }else{
                    $fecha_lanzamiento = $juego['released'];
                }

                // Obtener la imagen
                if($juego['background_image'] == null){
                    $imagen =  "https://www.teleadhesivo.com/es/img/arc226-jpg/folder/products-listado-merchanthover/pegatinas-coches-motos-space-invaders-marciano-iii.jpg"; // Si no hay imagen, asignar una imagen por defecto (Space Invaders)
                }else{
                    $imagen = $juego['background_image'];
                }

                // Obtener la calificación
                $calificacion = $juego['rating']; //Escala de 0 a 5.

                // Obtener la descripción
                $url2 = "https://api.rawg.io/api/games/" . $id_juego . "?key=" . $_ENV["API_KEY"];

                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, $url2);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Accept: application/json',
                    'Content-Type: application/json'
                ));
                $response2 = curl_exec($ch2);
                curl_close($ch2);

                // Decodificar la respuesta JSON
                $data2 = json_decode($response2, true);
                $descripcion = $data2['description'];
                
                $database_juego_genero = new Genero();

                // Guardar los datos en la base de datos.

                $database_juego = new Juego();
                $database_juego->create(
                    array(
                        'id' => $id_juego,
                        'nombre' => $nombre,
                        'descripcion' => $descripcion,
                        'imagen' => $imagen,
                        'Anyo_salida' => $fecha_lanzamiento,
                        'calificacion' => $calificacion,
                    )
                );
                echo "Juego: {$nombre} guardado en la base de datos.<br>";

                // Rellenamos los generos y las plataformas una vez añadido el juego para evitar errores a la hora de repetir el proceso de rellenar la BD. (Si fuera antes no daría el salto en el bucle al no tener el juego registrado en la BD.)

                // Rellenar los generos del Juego en la base de datos.
                $generos = $juego['genres'];
                foreach ($generos as $generoAPI) {
                    // Comprobar si el genero ya existe en la base de datos
                    $genero_bd = new Genero();

                    $genero_bd = $genero_bd->rellenarBDJuegosGeneros($id_juego,  $generoAPI['id']);
                }

                $plataformas = $juego['platforms'];
                foreach ($plataformas as $plataformaAPI) {
                    // Comprobar si la plataforma ya existe en la base de datos
                    $plataforma_bd = new Plataforma();

                    $plataforma_bd = $plataforma_bd->rellenarBDJuegosPlataforma($id_juego,  $plataformaAPI["platform"]['id']); //Para las plataformas, en la API, existe dentro de una plataforma el campo "platform"
                }
            }        
        }

        echo "Base de datos actualizada con éxito.";
    }
}