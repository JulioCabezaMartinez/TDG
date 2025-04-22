<?php

namespace App\Models;

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

    public function get10(): array {
        return parent::query("SELECT * FROM {$this->table} LIMIT 10")->fetchAll(\PDO::FETCH_ASSOC);
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

                if($juego_bd == $juego['id']){
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
                
                // Guardar los datos en la base de datos.
                $database = new Juego();
                $database->create(
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
            }        
        }

        echo "Base de datos actualizada con éxito.";
    }
}