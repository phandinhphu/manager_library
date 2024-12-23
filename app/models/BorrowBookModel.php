<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    

    /**
     * Tìm kiếm sách mượn
     * @param mixed $where
     * @param mixed $orderBy
     * @param mixed $status
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-24
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

    /***
     * r -> borrowbooks, u -> users, b -> books
     * @author Phan Đình Phú
     * @since 2024/11/17
     * @param array $where
     * @param string $selected
     * @param int $page
     * @return array
     */
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/17
     * @param int $id
     * @return array
     */
    public function getInfoReturnBook($id): array
    {
        $dataBase = $this->getByCondition(
            ['id' => $id],
            'r.id, b.id as book_id, u.id as user_id, u.user_name, b.book_name
            , r.borrow_date, r.due_date, r.quantity',
            1
        )['data'][0];

        $returnDate = date('Y-m-d H:i:s');

        $overDues = $this->getOverDueBooks($dataBase['due_date'], $returnDate);

        return [
            'id' => $dataBase['id'],
            'book_id' => $dataBase['book_id'],
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/17
     * @param string $dueDate
     * @param string $returnDate
     * @return int
     */
    public function getOverDueBooks($dueDate, $returnDate): int {
        $dueDate = strtotime($dueDate);
        $returnDate = strtotime($returnDate);

        $diff = $returnDate - $dueDate;
        $diff = floor($diff / (60 * 60 * 24));

        return $diff > 0 ? $diff : 0;
    }
    
    /***
     * @author Phan Đình Phú
     * @since 2024/11/21
     * @param int $year
     * @return array
     */
    public function getAmountBorrowedOverYear($year): array
    {
        $sql = "SELECT 
                    m.month,
                    IFNULL(b.borrow_count, 0) AS borrow_count
                FROM 
                    (SELECT 1 AS month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
                    SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION 
                    SELECT 11 UNION SELECT 12) m
                LEFT JOIN 
                    (
                        SELECT 
                            MONTH(borrow_date) AS month, 
                            COUNT(*) AS borrow_count
                        FROM 
                            borrowbooks
                        WHERE 
                            YEAR(borrow_date) = :year
                        GROUP BY 
                            MONTH(borrow_date)
                    ) b
                ON 
                    m.month = b.month
                ORDER BY 
                    m.month";

        return $this->db->getAll($sql, ['year' => $year]);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param int $userId
     * @param array $where
     * @param string $filter
     * @param int $page
     * @return array
     */
    public function getBooksReturned($userId, $where = [], $filter = '', $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $orderBy = 'bb.return_date DESC';

        // Xây dựng mảng $param và điều kiện
        $condition = !empty($where) ? implode(' AND ', array_map(function ($v) use (&$where) {
            if ($v == 'book_name') {
                $where[$v] = '%' . $where[$v] . '%';
                return "b.$v LIKE :$v";
            }

            return "$v = :$v";
        }, array_keys($where))) : '';

        // Xây dựng điều kiện filter
        switch ($filter) {
            case 'book_name_asc':
                $orderBy = 'b.book_name ASC';
                break;
            case 'book_name_desc':
                $orderBy = 'b.book_name DESC';
                break;
            case 'borrow_date_desc':
                $orderBy = 'bb.borrow_date DESC';
                break;
            case 'borrow_date_asc':
                $orderBy = 'bb.borrow_date ASC';
                break;
            case 'return_date_asc':
                $orderBy = 'bb.return_date ASC';
                break;
            case 'fine_amount_desc':
                $orderBy = 'f.fine_amount DESC';
                break;
            case 'fine_amount_asc':
                $orderBy = 'f.fine_amount ASC';
                break;
            default:
                $orderBy = 'bb.return_date DESC';
        }

        $sql = $condition != '' ?
            "SELECT 
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                bb.borrow_date as borrow_date,
                bb.return_date as return_date,
                bb.book_status as book_status,
                f.fine_amount as fine_amount
            FROM $this->table bb
            JOIN fines f ON bb.id = f.borrow_id
            JOIN books b ON bb.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE bb.user_id = :userId AND bb.return_date IS NOT NULL
            AND $condition
            ORDER BY $orderBy"
            :
            "SELECT 
                b.isbn_code as isbn_code,
                b.book_name as book_name,
                a.author_name as author_name,
                bb.borrow_date as borrow_date,
                bb.return_date as return_date,
                bb.book_status as book_status,
                f.fine_amount as fine_amount
            FROM $this->table bb
            JOIN fines f ON bb.id = f.borrow_id
            JOIN books b ON bb.book_id = b.id
            JOIN authors a ON b.author_id = a.id
            WHERE bb.user_id = :userId AND bb.return_date IS NOT NULL
            ORDER BY $orderBy";

        $total = count($this->db->getAll($sql, array_merge($where, ['userId' => $userId])));

        $rs = $this->db->getAll($sql . " LIMIT $offset, $this->limit", array_merge($where, ['userId' => $userId]));

        return [
            'data' => $rs,
            'total' => $total
        ];
    }

    /**
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param $userId
     * @param string $type 'all' || 'overdue'
     * @return int
     */
    public function sumBooksReturned($userId, $type = 'all'): int
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table WHERE user_id = :userId AND return_date IS NOT NULL";
        if ($type == 'overdue') {
            $sql .= " AND due_date < return_date";
        }

        return $this->db->getOne($sql, ['userId' => $userId])['total'];
    }

    /**
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param $userId
     * @param string $type 'all' || 'overdue'
     * @return array
     */
    public function sumBooksBorrow($userId, $type = 'all'): int
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table WHERE user_id = :userId AND return_date IS NULL";
        if ($type == 'overdue') {
            $sql .= " AND due_date < CURDATE()";
        }

        return $this->db->getOne($sql, ['userId' => $userId])['total'];
    }

    /**
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param $userId
     * @return int
     */
    public function sumFineAmount($userId): int
    {
        $sql = "SELECT SUM(fine_amount) as total FROM fines f
                JOIN borrowbooks bb ON f.borrow_id = bb.id
                WHERE bb.user_id = :userId";

        $rs = $this->db->getOne($sql, ['userId' => $userId])['total'];

        return $rs ?? 0;
    }

    /**
     * Lấy tổng số sách đang mượn và đã trả
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param int $userId
     * @return int
     */
    public function getTotal($userId): int
    {
        $sql = "SELECT COUNT(*) as total FROM $this->table 
                WHERE user_id = :userId";

        return $this->db->getOne($sql, ['userId' => $userId])['total'];

    }

    /* Lấy danh sách sách mượn theo id người dùng
     * @param mixed $id
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function getBorrowBookByUserId($id): array
    {
        $sql = 
        "SELECT 
            b.book_name AS book_name,
            bb.borrow_date AS borrow_date,
            bb.return_date AS return_date,
            bb.quantity AS quantity,
            IF(f.borrow_id = bb.id, 'Đã trả', 'Chưa trả') AS borrow_status,
            f.fine_amount AS fine
        FROM borrowbooks bb
        LEFT JOIN books b ON bb.book_id = b.id
        LEFT JOIN fines f ON bb.id = f.borrow_id
        WHERE bb.user_id = $id
        ";
        $reader = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $reader;
    }

    /**
     * Xuất file excel danh sách sách mượn theo id người dùng
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function exportExcelReader($userId): void
    {
        $sql = "SELECT
                    b.book_name AS book_name,
                    bb.borrow_date AS borrow_date,
                    bb.return_date AS return_date,
                    bb.quantity AS quantity,
                    IF(f.borrow_id = bb.id, CONCAT(bb.book_status,'(Đã trả)'), 'Chưa trả') AS borrow_status,
                    f.fine_amount AS fine
                FROM $this->table bb
                LEFT JOIN books b ON bb.book_id = b.id
                LEFT JOIN fines f ON bb.id = f.borrow_id
                WHERE bb.user_id = :user_id
                ORDER BY bb.borrow_date DESC";

        $borrowBooks = $this->db->getAll($sql,['user_id' => $userId]);

        $sql1 = "SELECT * FROM users WHERE id = :user_id";
        $user = $this->db->getOne($sql1, ['user_id' => $userId]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Thông tin mượn sách');

        // Thông tin khách hàng
        $sheet->setCellValue('A1', 'THÔNG TIN NGƯỜI MƯỢN');
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A2', 'Họ tên:');
        $sheet->setCellValue('B2', $user['user_name']);
        $sheet->setCellValue('A3', 'Email:');
        $sheet->setCellValue('B3', $user['email']);
        $sheet->setCellValue('A4', 'Số điện thoại:');
        $sheet->setCellValue('B4', $user['phone_number']);

        // Tiêu đề danh sách sách
        $sheet->setCellValue('A6', 'DANH SÁCH SÁCH ĐÃ MƯỢN');
        $sheet->mergeCells('A6:G6');

        // Header bảng sách mượn
        $sheet->setCellValue('A7', 'STT');
        $sheet->setCellValue('B7', 'Tên sách');
        $sheet->setCellValue('C7', 'Ngày mượn');
        $sheet->setCellValue('D7', 'Ngày trả');
        $sheet->setCellValue('E7', 'Số lượng');
        $sheet->setCellValue('F7', 'Trạng thái');
        $sheet->setCellValue('G7', 'Tiền phạt');

        // Style
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A6:G6')->getFont()->setBold(true);
        $sheet->getStyle('A7:G7')->getFont()->setBold(true);

        if(empty($borrowBooks)) {
            $sheet->setCellValue('A8', 'Không có dữ liệu');
            $sheet->mergeCells('A8:G8');
        } else {
            $row = 8;
            foreach($borrowBooks as $index => $book) {
                $sheet->setCellValue('A'.$row, $index + 1);
                $sheet->setCellValue('B'.$row, $book['book_name']);
                $sheet->setCellValue('C'.$row, $book['borrow_date']);
                $sheet->setCellValue('D'.$row, $book['return_date']);
                $sheet->setCellValue('E'.$row, $book['quantity']);
                $sheet->setCellValue('F'.$row, $book['borrow_status']);
                $sheet->setCellValue('G'.$row, number_format($book['fine'], 0, ',', '.') . ' VNĐ');
                $row++;
            }
        }
        
        // Auto size column
        foreach(range('A','G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $file_name = 'Thông tin sách mượn của ' . $user['user_name'] . '-' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        exit();
    }
}
