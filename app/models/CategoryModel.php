<?php
class CategoryModel extends Model
{
    public string $table = 'category';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy tất cả thể loại
     * @return array
     * @author Trần Duy Vương
     * @since 2024-10-31
     */
    public function getAllCategory(): array
    {
        return $this->getAll('*', 'all');
    }

    /**
     * Lấy thể loại theo trang
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-10-31
     */
    public function getByPage(int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $sql = "SELECT * 
                FROM $this->table 
                LIMIT $offset, $this->limit";

        $categories = $this->db->getAll($sql);

        $total = count($this->getAllCategory());

        return [
            'data' => $categories,
            'total' => $total
        ];
    }

    /**
     * Lấy 1 dòng dữ liệu
     * @param mixed $where
     * @param mixed $select
     * @return false|array
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
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

    /**
     * Kiểm tra thể loại
     * @param mixed $where
     * @param mixed $select
     * @return bool
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
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

    /**
     * Lấy các cột của bảng
     * @param mixed $table
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    private function getTableColumns($table): array
    {
        $sql = "SHOW COLUMNS FROM $table";
        $columns = $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        return $columns;
    }

    /**
     * Tìm kiếm thể loại
     * @param mixed $keyword
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
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
    
        $categories = $this->db->query($sql, ['keyword' => "%$keyword%"])->fetchAll(PDO::FETCH_ASSOC);
        $total = count($categories);

        return [
            'data' => $categories,
            'total' => $total
        ];
    }

    public function insertCategory($data): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }
}
