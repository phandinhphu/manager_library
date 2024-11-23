<?php

class AuthorModel extends Model
{
    public string $table = 'authors';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function getAllAuthors(): array
    {
        return $this->getAll('*', 'all');
    }

    public function getByPage(int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $sql = "SELECT * 
                FROM $this->table 
                LIMIT $offset, $this->limit";

        $author = $this->db->getAll($sql);

        $total = count($this->getAllAuthors());

        return [
            'data' => $author,
            'total' => $total
        ];
    }

    // Lấy 1 dòng dữ liệu
    public function getOne($where = [], $select = '*'): false|array
    {
        if (!empty($where)) {
            $condition = implode(' AND ', array_map(fn($item) => "$item = :$item", array_keys($where)));
            $sql = "SELECT $select FROM $this->table WHERE $condition";
        } else {
            $sql = "SELECT $select FROM $this->table";
        }

        return $this->db->query($sql, $where)->fetch(PDO::FETCH_ASSOC);
    }

    public function check($where = [], $select = '*'): bool
    {
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

    public function insert($data = [])
    {
        if (empty($data)) {
            return false;
        }
        if ($this->check(['author_name' => $data['author_name']])) {
            return false;
        }
        return $this->db->insert($this->table, $data);
    }

    // cập nhật tác giả
    public function update($data = [], $where = []): false|PDOStatement
    {
        if (empty($data)) {
            return false;
        }
        if ($this->check(['author_name' => $data['author_name']])) {
            return false;
        }
        return $this->db->update($this->table, $data, $where);
    }

    // xóa tác giả
    public function delete($where = []): false|PDOStatement
    {
        return $this->db->delete($this->table, $where);
    }

    // lấy các cột 
    private function getTableColumns($table): array
    {
        $sql = "SHOW COLUMNS FROM $table";
        $columns = $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        return $columns;
    }

    // tìm kiếm tác giả
    public function search($keyword, int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        // Lấy tên các cột của bảng
        $columns = $this->getTableColumns($this->table);

        // Xây dựng điều kiện tìm kiếm động
        $searchConditions = [];
        foreach ($columns as $column) {
            $searchConditions[] = "$column LIKE :keyword";
        }
        $searchQuery = implode(' OR ', $searchConditions);
        
        // Xây dựng truy vấn SQL
        $sql = "SELECT * 
                FROM $this->table 
                WHERE $searchQuery
                LIMIT $offset, $this->limit";
    
        $authors = $this->db->query($sql, ['keyword' => "%$keyword%"])->fetchAll(PDO::FETCH_ASSOC);
        $total = count($authors);

        return [
            'data' => $authors,
            'total' => $total
        ];
    }

    public function insertAuthor($data): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }
}