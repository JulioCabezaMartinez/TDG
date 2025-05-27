<?php

namespace App\Interfaces;

interface BusquedaAdmin {
    /**
     * Método para obtener una lista de ventas con filtros y paginación.
     *
     * @param int $inicio Índice de inicio para la paginación.
     * @param int $limit Número máximo de registros a devolver.
     * @param array $filtros Filtros aplicados a la búsqueda.
     * @return array Lista de ventas filtradas y paginadas.
     */
     public function buscarAdmin($textoBusqueda, $inicio, $limit);


    /**
     * Método para obtener una lista de ventas con filtros y paginación.
     *
     * @param int $inicio Índice de inicio para la paginación.
     * @param int $limit Número máximo de registros a devolver.
     * @param array $filtros Filtros aplicados a la búsqueda.
     * @return array Lista de ventas filtradas y paginadas.
     */
    public function buscarAdminCount($textoBusqueda);
}