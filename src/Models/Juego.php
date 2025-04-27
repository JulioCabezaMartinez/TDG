<?php

namespace App\Models;

use App\Core\EmptyModel;

/**
 * Modelo para gestionar las operaciones relacionadas con la tabla de juegos.
 */
class Juego extends EmptyModel {
    /**
     * Constructor de la clase Juego.
     * Configura la tabla y la clave primaria asociadas al modelo.
     */
    public function __construct() {
        parent::__construct('juegos', 'id');
    }

    public function getCount(): int {
        return (int) parent::query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    public function getNew(): array {
        return parent::query("SELECT * FROM {$this->table} ORDER BY Anyo_salida DESC LIMIT 10")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getListGames($inicio, $limit): array {
        return parent::query("SELECT * FROM {$this->table} LIMIT $inicio, $limit")->fetchAll(\PDO::FETCH_ASSOC);
    }

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