<?php

class BorrowBookModel extends Model
{
    public string $table = 'borrowbooks';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy tất cả sách mượn
     * @param int $user_id
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-13
     */
    public function getAllBorrowBooks(int $user_id): array
    {
        $where = ['user_id' => $user_id];
        $sql =
            "SELECT
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                r.create_date as borrow_date,
                NULL as due_date,
                'Đang xử lý' as status
            FROM request r
            JOIN books b ON r.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE r.user_id = :user_id
            
            UNION
            
            SELECT
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                bb.borrow_date as borrow_date,
                bb.due_date as due_date,
                'Đã mượn' as status
            FROM $this->table bb
            JOIN books b ON bb.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE bb.user_id = :user_id
        ";
        return $this->db->query($sql, $where)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sách mượn theo trang
     * @param mixed $where
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-13
     */
    public function getByPage($where, int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $sql =
            "SELECT * FROM (
                SELECT
                    b.isbn_code as isbn_code,
                    b.book_name as book_name,
                    a.author_name as author_name,
                    NULL as borrow_date,
                    NULL as due_date,
                    'Đang xử lý' as status
                FROM request r
                JOIN books b ON r.book_id = b.id
                JOIN authors a ON b.author_id = a.id
                WHERE r.user_id = :user_id

                UNION ALL

                SELECT
                    b.isbn_code as isbn_code,
                    b.book_name as book_name,
                    a.author_name as author_name,
                    bb.borrow_date as borrow_date,
                    bb.due_date as due_date,
                    'Đã mượn' as status
                FROM $this->table bb
                JOIN books b ON bb.book_id = b.id
                JOIN authors a ON b.author_id = a.id
                WHERE bb.user_id = :user_id
            ) AS combined LIMIT $offset, $this->limit
        ";
        $borrowed_books = $this->db->query($sql, $where)->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $borrowed_books,
            'total' => count($borrowed_books)
        ];
    }

    

    /**
     * Tìm kiếm sách mượn
     * @param mixed $where
     * @param mixed $orderBy
     * @param mixed $status
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-13
     */
    public function search($where, $orderBy = 1, $status = 0, int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $params = [];

        switch ($orderBy) {
            case 1:
                $orderBy = 'book_name ASC';
                break;
            case 2:
                $orderBy = 'author_name ASC';
                break;
            case 3:
                $orderBy = 'borrow_date DESC';
                break;
            case 4:
                $orderBy = 'due_date DESC';
                break;
            default:
                $orderBy = 'book_name ASC';
        }

        $conditions = !empty($where) ? implode(' AND ', array_map(function($v) use (&$params, $where) {
            if ($v === 'user_id') {
                $params['user_id'] = $where['user_id'];
                return "$v = :user_id";
            }
            if ($v === 'isbn_code') {
                $params['isbn_code'] = '%' . $where['isbn_code'] . '%';
                return "$v LIKE :isbn_code";
            }
            if ($v === 'book_name') {
                $params['book_name'] = '%' . $where['book_name'] . '%';
                return "$v LIKE :book_name";
            }
            if ($v === 'author_name') {
                $params['author_name'] = '%' . $where['author_name'] . '%';
                return "$v LIKE :author_name";
            }
            
            $params[$v] = $where[$v];
            return "$v = :$v";
        }, array_keys($where))) : '';

        $sql1 =
            "SELECT
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                NULL as borrow_date,
                NULL as due_date,
                'Đang xử lý' as status
            FROM request r
            JOIN books b ON r.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE $conditions";

        $sql2= 
            "SELECT
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                bb.borrow_date as borrow_date,
                bb.due_date as due_date,
                'Đã mượn' as status
            FROM $this->table bb
            JOIN books b ON bb.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE $conditions
            ";
        if($status == 0)
            $sql = "SELECT * FROM ($sql1 UNION ALL $sql2) AS combined ORDER BY $orderBy LIMIT $offset, $this->limit";
        else if($status == 1)
            $sql = "SELECT * FROM ($sql1) AS combined ORDER BY $orderBy LIMIT $offset, $this->limit";
        else
            $sql = "SELECT * FROM ($sql2) AS combined ORDER BY $orderBy LIMIT $offset, $this->limit";

        $results = $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return [
            'data' => $results,
            'total' => count($this->db->getAll($sql, $params))
        ];

    }

    

    
}
