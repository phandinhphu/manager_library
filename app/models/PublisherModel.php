<?php
class PublisherModel extends Model
{
    public string $table = 'publishers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPublishers(): array
    {
        return $this->getAll('*', 'all');
    }

    public function getByPage(int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $sql = "SELECT * 
                FROM $this->table 
                LIMIT $offset, $this->limit";

        $publisher = $this->db->getAll($sql);

        $total = count($this->getAllPublishers());

        return [
            'data' => $publisher,
            'total' => $total
        ];
    }

    public function check($where = [], $select = '*'): bool {
        if (!empty($where)) {
            $condition = implode(' AND ', array_map(fn($item) => "$item = :$item", array_keys($where)));
            $sql = "SELECT $select FROM $this->table WHERE $condition";
        } else {
            $sql = "SELECT $select FROM $this->table";
        }
        $row = $this->db->query($sql, $where)->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return true;
        }
        return false;
    }

    private function getTableColumns($table): array
    {
        $sql = "SHOW COLUMNS FROM $table";
        $columns = $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        return $columns;
    }

    public function getOne($where = [], $select = '*'): false|array {
        if (!empty($where)) {
            $condition = implode(' AND ', array_map(fn($item) => "$item = :$item", array_keys($where)));
            $sql = "SELECT $select FROM $this->table WHERE $condition";
        } else {
            $sql = "SELECT $select FROM $this->table";
        }

        return $this->db->query($sql, $where)->fetch(PDO::FETCH_ASSOC);
    }

    public function insertPublisher($data): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }

    public function updatePublisher($data, $where): false|PDOStatement
    {
        return $this->db->update($this->table, $data, $where);
    }

    public function deletePublisher($where): false|PDOStatement
    {
        return $this->db->delete($this->table, $where);
    }
}