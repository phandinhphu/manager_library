<?php
class BookModel extends Model
{
    public string $table = 'books';
    public string $tableBookActions = 'user_book_actions';

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
        return $this->getAll('*', 'all');
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

        $total = count($this->getAllBooks());

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

        if (!in_array($book, $_SESSION['books']['wishlist'])) {
            $_SESSION['books']['wishlist'][] = $book;
            return true;
        }

        return false;
    }
}
