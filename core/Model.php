<?php
class Model {
    protected Database $db;
    protected int $limit = 15;

    public function __construct() {
        $this->db = Database::GetInstance();
    }

    public function getAll($selected = '*', $page = 1): array
    {
        if ($page == 'all') {
            $sql = "SELECT $selected FROM $this->table";
            return $this->db->getAll($sql);
        }
        $offset = ($page - 1) * $this->limit;
        $sql = "SELECT $selected FROM $this->table LIMIT $offset, $this->limit";

        $total = count($this->db->get($this->table));
        return [
            'data' => $this->db->getAll($sql),
            'total' => $total
        ];
    }

    public function getByCondition($where = [], $selected = '*', $page = 1): array
    {
        if ($page == 'all') {
            return $this->db->get($this->table, $where, $selected);
        }
        $offset = ($page - 1) * $this->limit;
        $condition = implode(' AND ', array_map(fn($v) => "$v = :$v", array_keys($where)));
        $sql = !empty($where) ?
            "SELECT $selected FROM $this->table WHERE $condition LIMIT $offset, $this->limit" :
            "SELECT $selected FROM $this->table LIMIT $offset, $this->limit";
        return $this->db->getAll($sql);
    }

    public function getDetail($id): false|array
    {
        return $this->db->get($this->table, ['id' => $id], '*');
    }

    public function getTotalPage($total): int
    {
        return ceil($total/$this->limit);
    }

    public function Add($data = []): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }

    public function Update($data = [], $where = []): false|PDOStatement
    {
        return $this->db->update($this->table, $data, $where);
    }

    public function Delete($where = []): false|PDOStatement
    {
        return $this->db->delete($this->table, $where);
    }
}