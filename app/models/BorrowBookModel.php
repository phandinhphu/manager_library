<?php

class BorrowBookModel extends Model
{
    public string $table = 'borrowbooks';

    public function __construct()
    {
        parent::__construct();
    }

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
            WHERE bb.user_id = :user_id AND bb.return_date IS NULL AND bb.book_status IS NULL
        ";
        return $this->db->query($sql, $where)->fetchAll(PDO::FETCH_ASSOC);
    }

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
                WHERE bb.user_id = :user_id AND bb.return_date IS NULL AND bb.book_status IS NULL
            ) AS combined LIMIT $offset, $this->limit
        ";
        $borrowed_books = $this->db->query($sql, $where)->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $borrowed_books,
            'total' => count($borrowed_books)
        ];
    }

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
            WHERE $conditions AND bb.return_date IS NULL AND bb.book_status IS NULL
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @return array
     */
    public function getNewReaderBorrowing(): array
    {
        $sql = "SELECT * FROM borrowbooks, users, books
                WHERE borrowbooks.user_id = users.id AND borrowbooks.book_id = books.id
                AND return_date IS NULL AND book_status IS NULL
                ORDER BY borrowbooks.id DESC LIMIT 0, 5";
        
        $rs = $this->db->getAll($sql);

        if ($rs) return $rs;
        return [];
    }
    
    /***
     * @author Phan Đình Phú
     * @since 2024/11/17
     * @param int $page
     * @return array
     */
    public function getAll($selected = '*', $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        
        $sql = "SELECT $selected FROM $this->table r, users u, books b
                WHERE r.user_id = u.id AND r.book_id = b.id
                AND return_date IS NULL AND book_status IS NULL
                ORDER BY r.borrow_date DESC";

        $total = count($this->db->getAll($sql));

        return [
            'data' => $this->db->getAll("$sql LIMIT $offset, $this->limit"),
            'total' => $total
        ];
    }

    public function getByCondition($where = [], $selected = '*', $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $params = [];

        $conditions = !empty($where) ? implode(' AND ', array_map(function($v) use (&$params, $where) {
            if ($v === 'id') {
                $params['id'] = $where['id'];
                return "r.$v = :id";
            }
            if ($v === 'user_name') {
                $params['user_name'] = '%' . $where['user_name'] . '%';
                return "u.$v LIKE :user_name";
            }
            if ($v === 'isbn_code') {
                $params['isbn_code'] = '%' . $where['isbn_code'] . '%';
                return "b.$v LIKE :isbn_code";
            }
            if ($v === 'book_name') {
                $params['book_name'] = '%' . $where['book_name'] . '%';
                return "b.$v LIKE :book_name";
            }

            $params[$v] = $where[$v];
            return "$v = :$v";
        }, array_keys($where))) : '';

        if ($conditions === '') return $this->getAll($selected, $page);

        $sql = "SELECT $selected FROM $this->table r, users u, books b
                WHERE r.user_id = u.id AND r.book_id = b.id
                AND return_date IS NULL AND book_status IS NULL AND $conditions
                ORDER BY r.borrow_date DESC LIMIT $offset, $this->limit";

        $total = count($this->db->getAll($sql, $params));

        return [
            'data' => $this->db->getAll($sql, $params),
            'total' => $total
        ];
    }

    public function getInfoReturnBook($id): array
    {
        $dataBase = $this->getByCondition(
            ['id' => $id],
            'r.id, u.id as user_id, u.user_name, b.book_name, r.borrow_date, r.due_date, r.quantity',
            1
        )['data'][0];

        $returnDate = date('Y-m-d H:i:s');

        $overDues = $this->getOverDueBooks($dataBase['due_date'], $returnDate);

        return [
            'id' => $dataBase['id'],
            'user_id' => $dataBase['user_id'],
            'user_name' => $dataBase['user_name'],
            'book_name' => $dataBase['book_name'],
            'borrow_date' => $dataBase['borrow_date'],
            'due_date' => $dataBase['due_date'],
            'return_date' => $returnDate,
            'quantity' => $dataBase['quantity'],
            'over_dues' => $overDues,
            'system_fine' => $overDues * 5000
        ];
    }

    public function getOverDueBooks($dueDate, $returnDate): int {
        $dueDate = strtotime($dueDate);
        $returnDate = strtotime($returnDate);

        $diff = $returnDate - $dueDate;
        $diff = floor($diff / (60 * 60 * 24));

        return $diff > 0 ? $diff : 0;
    }
}
