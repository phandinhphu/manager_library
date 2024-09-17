<?php
class Database {
    private static ?Database $instance = null;
    private ?PDO $__conn = null;

    private function __construct() {
        $this->__conn = Connection::GetInstance()->GetConnection();
    }

    public static function GetInstance(): Database
    {
        return self::$instance ?? self::$instance = new Database();
    }

    public function query($sql, $params = []): false|PDOStatement
    {
        $stmt = $this->__conn->prepare($sql);
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
    }

    public function insert($table, $data = []): false|PDOStatement
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($v) => ":$v", array_keys($data)));
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";
        return $this->query($sql, $data);
    }

    public function update($table, $data = [], $where = []): false|PDOStatement
    {
        $set = implode(', ', array_map(fn($v) => "$v = :$v", array_keys($data)));
        $condition = implode(' AND ', array_map(fn($v) => "$v = :$v", array_keys($where)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->query($sql, array_merge($data, $where));
    }

    public function delete($table, $where = []): false|PDOStatement
    {
        $condition = implode(' AND ', array_map(fn($v) => "$v = :$v", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->query($sql, $where);
    }

    public function get($table, $where = [], $select = '*'): false|array
    {
        if (!empty($where)) {
            $condition = implode(' AND ', array_map(fn($item) => "$item = :$item", array_keys($where)));
            $sql = "SELECT $select FROM $table WHERE $condition";
        } else {
            $sql = "SELECT $select FROM $table";
        }
        return $this->query($sql, $where)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($sql, $data = []): array|false
    {
        return $this->query($sql, $data)->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($sql, $data = []): array
    {
        return $this->query($sql, $data)->fetchAll(PDO::FETCH_ASSOC);
    }
}