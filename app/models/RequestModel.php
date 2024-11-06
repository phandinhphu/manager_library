<?php

class RequestModel extends Model
{
    public string $table = 'request';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/5
     * @param string $selected
     * @param int $page
     * @return array
     */
    public function getAll($selected = '*', $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $sql = "SELECT $selected FROM $this->table r, books b, users u
                WHERE r.book_id = b.id AND r.user_id = u.id
                ORDER BY r.create_date DESC";

        $total = $this->db->getAll($sql);

        return [
            'data' => $this->db->getAll("$sql LIMIT $this->limit OFFSET $offset"),
            'total' => count($total)
        ];
    }

    public function getByCondition($where = [], $selected = '*', $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $condition = !empty($where) 
                    ? implode(' AND ', array_map(function ($v) use (&$where) {
                        if ($v == 'user_name') {
                            $where[$v] = '%' . $where[$v] . '%';
                            return "u.user_name LIKE :$v";
                        }
                        return "$v = :$v";
                    }, array_keys($where))) 
                    : '';

        $sql = "SELECT $selected FROM $this->table r, books b, users u
                WHERE r.book_id = b.id AND r.user_id = u.id";

        if ($condition != '') {
            $sql .= " AND $condition";
        }

        $sql .= " ORDER BY r.create_date DESC";

        $total = $this->db->getAll($sql, $where);

        return [
            'data' => $this->db->getAll("$sql LIMIT $this->limit OFFSET $offset", $where),
            'total' => count($total)
        ];
    }
}