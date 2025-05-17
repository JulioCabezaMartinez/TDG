<?php

namespace App\Core;

use App\Config\Database;
use PDO;
use PDOStatement;

/**
 * Modelo base vacío que puede ser extendido por otros modelos.
 */
abstract class EmptyModel {
    protected $db;
    protected $table;
    protected $primaryKey;

    /**
     * Constructor de la clase EmptyModel.
     * Inicializa el modelo base.
     */
    public function __construct($table, $primaryKey = 'id') {
        $this->db = Database::getInstance()->getConnection();
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Método genérico para ejecutar consultas SQL.
     *
     * @param string $sql Consulta SQL.
     * @param array $params Parámetros de la consulta.
     * @return PDOStatement Resultado de la consulta.
     */
    protected function query($sql, $params = []): bool|PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Método genérico para obtener todos los registros.
     *
     * @return array Lista de registros.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Método genérico para obtener un registro por su ID.
     *
     * @param int $id ID del registro a obtener.
     * @return mixed Datos del registro o null si no se encuentra.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    public function compruebaCampo($campo, $valor) {
        $sql = "SELECT * FROM {$this->table} WHERE {$campo} = ?";
        return $this->query($sql, [$valor])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Método genérico para crear un nuevo registro.
     *
     * @param array $data Datos del nuevo registro.
     * @return int ID del registro creado.
     */
    public function create($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * Método genérico para actualizar un registro.
     *
     * @param int $id ID del registro a actualizar.
     * @param array $data Datos actualizados del registro.
     * @return void
     */
    public function update($data, $id): void {
        $setClause = implode(', ', array_map(fn($field) => "{$field} = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        $this->query($sql, array_merge(array_values($data), [$id]));
    }

    /**
     * Método genérico para eliminar un registro.
     *
     * @param int $id ID del registro a eliminar.
     * @return void
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->query($sql, [$id]);
    }

    public function listaColumnas(){
        $sql="SHOW COLUMNS from {$this->table};";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }
        public function getCount(): int {
        return (int) $this->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
?>