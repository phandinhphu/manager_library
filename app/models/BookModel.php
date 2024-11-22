<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BookModel extends Model
{
    public string $table = 'books';
    public string $tableBookActions = 'user_book_actions';
    public string $tableBorrowBooks = 'borrowbooks';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @return array
     */
    public function getAllBooks(): array
    {
        $sql = "SELECT $this->table.*, authors.author_name as author_name, publishers.publisher_name as publisher_name
                FROM $this->table, authors, publishers
                WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id";

        $books = $this->db->getAll($sql);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id 
                                            and book_id = :book_id", ['book_id' => $book['id']]);

            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        $total = count($newBooks);

        return [
            'data' => $newBooks,
            'total' => $total
        ];
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param int $page
     * @return array
     */
    public function getBooksByPage(int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;

        $sql = "SELECT $this->table.*, authors.author_name as author_name, publishers.publisher_name as publisher_name
                FROM $this->table, authors, publishers
                WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
                LIMIT $offset, $this->limit";

        $books = $this->db->getAll($sql);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id 
                                            and book_id = :book_id", ['book_id' => $book['id']]);

            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        $total = $this->getAllBooks()['total'];

        return [
            'data' => $newBooks,
            'total' => $total
        ];
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param $id
     * @return array
     */
    public function getBookById($id): array
    {
        $sql = "SELECT $this->table.*, authors.author_name, publishers.publisher_name  
                FROM $this->table, authors, publishers
                WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
                and $this->table.id = :id";

        $book = $this->db->getAll($sql, ['id' => $id])[0];

        $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id 
                                            and book_id = :book_id", ['book_id' => $book['id']]);

        $book['categories'] = implode(', ', array_column($categories, 'category_name'));

        return $book;
    }

    /***
     * @author Phan Đình Phú
     * @param $where array
     * @param $page int
     * @return array
     * @since 2024/10/15
     */
    public function searchBooks($where, $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $param = [];

        // Xây dựng mảng $param và điều kiện
        $condition = !empty($where) ? implode(' AND ', array_map(function ($v) use (&$param, $where) {
            if ($v == 'categories') {
                $categories = explode(',', $where[$v]);
                $placeholders = implode(', ', array_fill(0, count($categories), '?'));
                $param = array_merge($param, $categories);

                return "book_categories.category_id IN ($placeholders)";
            }
            if ($v == 'book_name') {
                $param[] = '%' . $where[$v] . '%';
                return "$this->table.$v LIKE ?";
            }
            $param[] = $where[$v];
            return "$v = ?";
        }, array_keys($where))) : '';

        // Câu lệnh SQL
        $sql =
            $condition != '' ?
            "SELECT $this->table.*, authors.author_name, publishers.publisher_name 
            FROM $this->table, authors, publishers, book_categories, category
            WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
            and $this->table.id = book_categories.book_id AND book_categories.category_id = category.id
            and $condition
            GROUP BY $this->table.id"
            :
            "SELECT $this->table.*, authors.author_name, publishers.publisher_name 
            FROM $this->table, authors, publishers, book_categories, category
            WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
            and $this->table.id = book_categories.book_id AND book_categories.category_id = category.id
            GROUP BY $this->table.id"
        ;

        // Lấy dữ liệu từ DB với params
        $books = $this->db->getAll($sql . " LIMIT $offset, $this->limit", $param);

        // Xử lý các category cho từng cuốn sách
        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id 
                                            and book_id = :book_id", ['book_id' => $book['id']]);

            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        return [
            'data' => $newBooks,
            'total' => count($this->db->getAll($sql, $param))
        ];
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param $data
     * @return string|bool
     */
    public function insertBook($data): string|bool
    {
        $this->db->insert($this->table, $data);

        $bookId = $this->db->getLastId();

        return $bookId;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param $condition
     * @return string
     */
    public function createParamString($condition): string
    {
        $paramString = [];
        foreach ($condition as $key => $value) {
            if ($key == 'categories') {
                $categories = explode(',', $value);
                foreach ($categories as $category) {
                    $paramString[] = htmlspecialchars('category[]=') . $category;
                }
            } else {
                $paramString[] = $key . '=' . $value;
            }
        }

        return implode('&', $paramString);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/19
     * @param $book_id
     * @return int
     */
    public function getAmountLiked($book_id): int
    {
        $sql = "SELECT COUNT(*) as total_likes FROM $this->tableBookActions
                WHERE book_id = :book_id AND action_type = 'like'";

        $rs = $this->db->getOne($sql, ['book_id' => $book_id]);
        if ($rs) return $rs['total_likes'];
        return 0;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/19
     * @param $book_id
     * @return int
     */
    public function getAmountDisliked($book_id): int
    {
        $sql = "SELECT COUNT(*) as total_dislikes FROM $this->tableBookActions
                WHERE book_id = :book_id AND action_type = 'dislike'";

        $rs = $this->db->getOne($sql, ['book_id' => $book_id]);

        if ($rs) return $rs['total_dislikes'];
        return 0;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $book_id
     * @return void
     */
    public function like($book_id): void
    {
        $sql = "SELECT * FROM $this->tableBookActions
                WHERE book_id = :book_id AND user_id = :user_id";

        $rs = $this->db->getOne($sql, ['book_id' => $book_id, 'user_id' => $_SESSION['user']['user_id']]);

        if ($rs) {
            if ($rs['action_type'] == 'like') {
                $this->db->delete($this->tableBookActions, ['id' => $rs['id']]);
            } else {
                $this->db->update($this->tableBookActions, [
                    'action_type' => 'like',
                    'action_date' => date('Y-m-d H:i:s')
                ], ['id' => $rs['id']]);
            }
        } else {
            $this->db->insert($this->tableBookActions, [
                'book_id' => $book_id,
                'user_id' => $_SESSION['user']['user_id'],
                'action_type' => 'like',
                'action_date' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $book_id
     * @return void
     */
    public function dislike($book_id): void
    {
        $sql = "SELECT * FROM $this->tableBookActions
                WHERE book_id = :book_id AND user_id = :user_id";

        $rs = $this->db->getOne($sql, ['book_id' => $book_id, 'user_id' => $_SESSION['user']['user_id']]);

        if ($rs) {
            if ($rs['action_type'] == 'dislike') {
                $this->db->delete($this->tableBookActions, ['id' => $rs['id']]);
            } else {
                $this->db->update($this->tableBookActions, [
                    'action_type' => 'dislike',
                    'action_date' => date('Y-m-d H:i:s')
                ], ['id' => $rs['id']]);
            }
        } else {
            $this->db->insert($this->tableBookActions, [
                'book_id' => $book_id,
                'user_id' => $_SESSION['user']['user_id'],
                'action_type' => 'dislike',
                'action_date' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $book_id
     * @return string
     */
    public function getAction($book_id): string
    {
        if (!isset($_SESSION['user']['user_id'])) {
            return '';
        }

        $sql = "SELECT action_type FROM $this->tableBookActions
                WHERE book_id = :book_id AND user_id = :user_id";

        $rs = $this->db->getOne($sql, ['book_id' => $book_id, 'user_id' => $_SESSION['user']['user_id']]);

        if ($rs) return $rs['action_type'];
        return '';
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/27
     * @param $bookId
     * @return array
     */
    public function getSameCategoryBooks($bookId): array
    {
        $categories = $this->db->getAll("SELECT category_id FROM book_categories
                                        WHERE book_id = :book_id", ['book_id' => $bookId]);

        $books = $this->db->getAll("SELECT $this->table.*, authors.author_name, publishers.publisher_name 
                                    FROM $this->table, authors, publishers, book_categories
                                    WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
                                    and $this->table.id = book_categories.book_id AND book_categories.category_id IN 
                                    (" . implode(', ', array_column($categories, 'category_id')) . ")
                                    and $this->table.id != :book_id
                                    GROUP BY $this->table.id
                                    LIMIT 0, 5", ['book_id' => $bookId]);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id
                                            and book_id = :book_id", ['book_id' => $book['id']]);
            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        return $newBooks;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/27
     * @param $bookId
     * @return array
     */
    public function getSameAuthorBooks($bookId): array
    {
        $authorId = $this->db->getOne("SELECT author_id FROM $this->table WHERE id = :book_id", ['book_id' => $bookId]);

        $books = $this->db->getAll("SELECT $this->table.*, authors.author_name, publishers.publisher_name 
                                    FROM $this->table, authors, publishers
                                    WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
                                    and $this->table.author_id = :author_id
                                    and $this->table.id != :book_id
                                    GROUP BY $this->table.id
                                    LIMIT 0, 5", ['author_id' => $authorId['author_id'], 'book_id' => $bookId]);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category
                                            .id and book_id = :book_id", ['book_id' => $book['id']]);
            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        return $newBooks;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/31
     * @param $bookId
     * @return bool
     */
    public function addWishList($bookId): bool
    {
        if (!isset($_SESSION['books']['wishlist'])) {
            $_SESSION['books']['wishlist'] = [];
        }

        $book = $this->getBookById($bookId);

        if (!in_array($book, $_SESSION['books']['wishlist']) && $book['quantity'] > 0) {
            $_SESSION['books']['wishlist'][] = $book;
            return true;
        }

        return false;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/31
     * @param $bookId
     * @return bool
     */
    public function removeFromWishList($bookId): bool
    {
        $book = $this->getBookById($bookId);

        if (in_array($book, $_SESSION['books']['wishlist'])) {
            $key = array_search($book, $_SESSION['books']['wishlist']);
            unset($_SESSION['books']['wishlist'][$key]);
            return true;
        }

        return false;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/3
     * @param $page
     * @param array $condition
     * @return array
     */
    public function getBooksWishListByPage($page, $condition = []): array
    {
        if (!isset($_SESSION['books']['wishlist'])) {
            return [
                'data' => [],
                'total' => 0
            ];
        }

        $offset = ($page - 1) * $this->limit;

        $books = array_slice($this->searchBooksInWishList($condition), $offset, $this->limit);

        return [
            'data' => $books,
            'total' => count(isset($condition) ? $this->searchBooksInWishList($condition) : $_SESSION['books']['wishlist'])
        ];
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/3
     * @param $condition
     * @return array
     */
    public function searchBooksInWishList($condition): array
    {
        $books = array_filter($_SESSION['books']['wishlist'], function ($book) use ($condition) {
            if (isset($condition['book_name']) && $condition['book_name'] != '') {
                if (!str_contains($book['book_name'], $condition['book_name'])) {
                    return false;
                }
            }

            if (isset($condition['author_id']) && $condition['author_id'] != '') {
                if ($book['author_id'] != $condition['author_id']) {
                    return false;
                }
            }

            if (isset($condition['publisher_id']) && $condition['publisher_id'] != '') {
                if ($book['publisher_id'] != $condition['publisher_id']) {
                    return false;
                }
            }

            if (isset($condition['isbn_code']) && $condition['isbn_code'] != '') {
                if ($book['isbn_code'] != $condition['isbn_code']) {
                    return false;
                }
            }

            if (isset($condition['year_published']) && $condition['year_published'] != '') {
                if ($book['year_published'] != $condition['year_published']) {
                    return false;
                }
            }

            return true;
        });

        return $books;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @return array
     */
    public function getNewImportedBooks(): array
    {
        $sql = "SELECT $this->table.*, authors.author_name, publishers.publisher_name 
                FROM $this->table, authors, publishers
                WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id
                ORDER BY $this->table.id DESC
                LIMIT 0, 5";

        $books = $this->db->getAll($sql);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id
                                            and book_id = :book_id", ['book_id' => $book['id']]);
            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            return $book;
        }, $books);

        return $newBooks;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/21
     * @return array
     */
    public function statistic($condition = [], $page = 1): array
    {
        if (!empty($condition)) {
            $books = $this->searchBooks($condition, $page);
        } else {
            $books = $this->getBooksByPage($page);
        }

        $newBooks = array_map(function ($book) {
            $quantityBorrow = $this->db->getOne("SELECT SUM(quantity) as total
                                                FROM $this->tableBorrowBooks bb
                                                WHERE book_id = :book_id AND bb.return_date IS NULL AND
                                                bb.book_status IS NULL", ['book_id' => $book['id']]);

            $book['quantity_borrow'] = $quantityBorrow['total'] ?? 0;
            
            return $book;
        }, $books['data']);

        $totalBooks = $books['total'];

        return [
            'data' => $newBooks,
            'total' => $totalBooks
        ];
    }
    /***
     * author La Đại Lộc
     * @param 2024/11/22
     * @return array
     */

    public function getNewBooks(): array
    {
        $sql = "SELECT books.* 
                FROM books 
                ORDER BY id DESC
                LIMIT 0, 10";

        $books = $this->db->getAll($sql);
        return $books;
    }

    /***
     * author La Đại Lộc
     * @param 2024/11/22
     * @return array
     */
    public function getMostLikedBooks(): array
    {
        $sql = "SELECT books.*, COUNT(user_book_actions.book_id) as total_like
                FROM books
                JOIN user_book_actions ON books.id = user_book_actions.book_id
                WHERE user_book_actions.action_type = 'like'
                GROUP BY books.id
                ORDER BY total_like DESC
                LIMIT 0, 10";

        $books = $this->db->getAll($sql);
        return $books;
    }

    /***
     * author La Đại Lộc
     * @param 2024/11/22
     * @return array
     */
    public function getMostDislikedBooks(): array
    {
        $sql = "SELECT books.*, COUNT(user_book_actions.book_id) as total_dislike
                FROM books
                JOIN user_book_actions ON books.id = user_book_actions.book_id
                WHERE user_book_actions.action_type = 'dislike'
                GROUP BY books.id
                ORDER BY total_dislike DESC
                LIMIT 0, 10";

        $books = $this->db->getAll($sql);
        return $books;
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/21
     * @return void
     */
    public function exportExcelAllBooks(): void
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Thống kê toàn bộ sách');

        $sheet->setCellValue('A1', 'Mã ISBN')
            ->setCellValue('B1', 'Tên sách')
            ->setCellValue('C1', 'Tác giả')
            ->setCellValue('D1', 'Nhà xuất bản')
            ->setCellValue('E1', 'Năm xuất bản')
            ->setCellValue('F1', 'Thể loại')
            ->setCellValue('G1', 'Số lượng mượn')
            ->setCellValue('H1', 'Số lượng tồn')
            ->setCellValue('I1', 'Giá')
            ->setCellValue('J1', 'Location');
        
        $sql = "SELECT $this->table.*, authors.author_name as author_name, publishers.publisher_name as publisher_name
                FROM $this->table, authors, publishers
                WHERE $this->table.author_id = authors.id and $this->table.publisher_id = publishers.id";

        $books = $this->db->getAll($sql);

        $newBooks = array_map(function ($book) {
            $categories = $this->db->getAll("SELECT category_name FROM book_categories, category
                                            WHERE book_categories.category_id = category.id 
                                            and book_id = :book_id", ['book_id' => $book['id']]);

            $book['categories'] = implode(', ', array_column($categories, 'category_name'));

            $quantityBorrow = $this->db->getOne("SELECT SUM(quantity) as total
                                                FROM $this->tableBorrowBooks bb
                                                WHERE book_id = :book_id AND bb.return_date IS NULL AND
                                                bb.book_status IS NULL", ['book_id' => $book['id']]);

            $book['quantity_borrow'] = $quantityBorrow['total'] ?? 0;

            return $book;
        }, $books);

        $row = 2;
        
        foreach ($newBooks as $book) {
            $sheet->setCellValue('A' . $row, $book['isbn_code'])
                ->setCellValue('B' . $row, $book['book_name'])
                ->setCellValue('C' . $row, $book['author_name'])
                ->setCellValue('D' . $row, $book['publisher_name'])
                ->setCellValue('E' . $row, $book['year_published'])
                ->setCellValue('F' . $row, $book['categories'])
                ->setCellValue('G' . $row, $book['quantity_borrow'])
                ->setCellValue('H' . $row, $book['quantity'])
                ->setCellValue('I' . $row, $book['price'] . ' VND')
                ->setCellValue('J' . $row, $book['location']);

            $row++;
        }
        
        $file_name = 'Thong_ke_sach_' . date('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        exit();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/22
     * @param $bookId
     * @return void
     */
    public function exportExcelBook($bookId): void
    {
        $spreadsheet = new Spreadsheet();

        $sheet1 = $spreadsheet->getActiveSheet();

        $sheet1->setTitle('Thống kê sách');

        $sheet1->setCellValue('A1', 'Mã ISBN')
            ->setCellValue('B1', 'Tên sách')
            ->setCellValue('C1', 'Tác giả')
            ->setCellValue('D1', 'Nhà xuất bản')
            ->setCellValue('E1', 'Năm xuất bản')
            ->setCellValue('F1', 'Thể loại')
            ->setCellValue('G1', 'Số lượng mượn')
            ->setCellValue('H1', 'Số lượng tồn')
            ->setCellValue('I1', 'Giá')
            ->setCellValue('J1', 'Location');

        $book = $this->getBookById($bookId);

        $quantityBorrow = $this->db->getOne("SELECT SUM(quantity) as total
                                                FROM $this->tableBorrowBooks bb
                                                WHERE book_id = :book_id AND bb.return_date IS NULL AND
                                                bb.book_status IS NULL", ['book_id' => $book['id']]);

        $book['quantity_borrow'] = $quantityBorrow['total'] ?? 0;

        $sheet1->setCellValue('A2', $book['isbn_code'])
            ->setCellValue('B2', $book['book_name'])
            ->setCellValue('C2', $book['author_name'])
            ->setCellValue('D2', $book['publisher_name'])
            ->setCellValue('E2', $book['year_published'])
            ->setCellValue('F2', $book['categories'])
            ->setCellValue('G2', $book['quantity_borrow'])
            ->setCellValue('H2', $book['quantity'])
            ->setCellValue('I2', $book['price'] . ' VND')
            ->setCellValue('J2', $book['location']);
        
        $sheet2 = $spreadsheet->createSheet();

        $sheet2->setTitle('Thông tin độc giả mượn sách');

        $sheet2->setCellValue('A1', 'Mã độc giả')
            ->setCellValue('B1', 'Tên độc giả')
            ->setCellValue('C1', 'Số lượng mượn')
            ->setCellValue('D1', 'Ngày mượn')
            ->setCellValue('E1', 'Ngày trả')
            ->setCellValue('F1', 'Trạng thái')
            ->setCellValue('G1', 'Số ngày quá hạn')
            ->setCellValue('H1', 'Tiền phạt');

        $borrowBooks = $this->db->getAll("SELECT users.user_name, users.id as user_id, bb.quantity, 
                                        bb.borrow_date, bb.return_date, bb.book_status, f.fine_amount, f.days_overdue 
                                        FROM $this->tableBorrowBooks bb, users, fines f
                                        WHERE bb.user_id = users.id AND bb.book_id = :book_id
                                        AND bb.id = f.borrow_id AND bb.return_date IS NOT NULL 
                                        AND bb.book_status IS NOT NULL", 
                                        ['book_id' => $bookId]);


        if (empty($borrowBooks)) {
            $sheet2->setCellValue('A2', 'Không có dữ liệu');
        } else {
            $row = 2;
    
            foreach ($borrowBooks as $borrowBook) {
                $sheet2->setCellValue('A' . $row, $borrowBook['user_id'])
                    ->setCellValue('B' . $row, $borrowBook['user_name'])
                    ->setCellValue('C' . $row, $borrowBook['quantity'])
                    ->setCellValue('D' . $row, $borrowBook['borrow_date'])
                    ->setCellValue('E' . $row, $borrowBook['return_date'])
                    ->setCellValue('F' . $row, $borrowBook['book_status'])
                    ->setCellValue('G' . $row, $borrowBook['days_overdue'] . ' ngày')
                    ->setCellValue('H' . $row, $borrowBook['fine_amount'] . ' VND');
    
                $row++;
            }
        }


        $file_name = 'Thong_ke_sach_' . $book['book_name'] . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        exit();
    }
}
