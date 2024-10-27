<?php

class Books extends Controller
{
    private mixed $bookModel;
    private mixed $categoryModel;
    private mixed $authorModel;
    private mixed $publisherModel;
    private mixed $bookCategoriesModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->authorModel = $this->model('AuthorModel');
        $this->publisherModel = $this->model('PublisherModel');
        $this->bookCategoriesModel = $this->model('BookCategoriesModel');
    }

    /***
     * @author Phan Đình Phú
     * @param int $page
     * @return void
     * @since 2024/10/14
     */
    public function index(int $page = 1): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        $books = $this->bookModel->getBooksByPage($page);
        $this->data['headercontent']['tab'] = 'books';
        $this->data['subcontent']['books'] = $books['data'];
        $this->data['subcontent']['categories'] = $this->categoryModel->getAll('*', 'all');
        $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
        $this->data['subcontent']['publishers'] = $this->publisherModel->getAllPublishers();
        $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($books['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/books/trang-';

        $this->data['title'] = 'Books';
        $this->data['content'] = 'admin/books';

        $this->view('layouts/admin_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/12/14
     * @return void
     */
    public function addBook(): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataReq = [
                'isbn_code' => trim($_POST['code_isbn']),
                'book_name' => trim($_POST['book_name']),
                'book_title' => trim($_POST['book_title']),
                'author_id' => trim($_POST['author']),
                'publisher_id' => trim($_POST['publisher']),
                'category' => $_POST['category'],
                'year_published' => trim($_POST['year_published']),
                'quantity' => trim($_POST['quantity']),
                'price' => trim($_POST['price']),
                'book_description' => trim($_POST['book_description']),
            ];

            $checkImg = $this->processImg('img_book');
            if ($checkImg['status'] == 'error') {
                echo json_encode([
                    'status' => 'error',
                    'message' => $checkImg['message']
                ]);
                return;
            }

            $dataReq['img'] = $checkImg['path'];

            $dataInsertBook  = [
                'isbn_code' => $dataReq['isbn_code'],
                'book_name' => $dataReq['book_name'],
                'book_title' => $dataReq['book_title'],
                'book_description' => $dataReq['book_description'],
                'author_id' => $dataReq['author_id'],
                'publisher_id' => $dataReq['publisher_id'],
                'year_published' => $dataReq['year_published'],
                'quantity' => $dataReq['quantity'],
                'price' => $dataReq['price'],
                'img' => $dataReq['img'],
                'likes' => 0,
                'dislikes' => 0,
            ];

            $bookId = $this->bookModel->insertBook($dataInsertBook);

            if ($bookId) {
                foreach ($dataReq['category'] as $category) {
                    $dataInsertCategory = [
                        'book_id' => $bookId,
                        'category_id' => $category
                    ];

                    $this->bookCategoriesModel->insertBookCategories($dataInsertCategory);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Thêm sách thành công'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Thêm sách thất bại'
                ]);
                return;
            }
        } else {
            $this->data['headercontent']['tab'] = 'books';
            $this->data['subcontent']['title'] = 'Create Book';
            $this->data['subcontent']['category'] = $this->categoryModel->getAll('*', 'all');
            $this->data['subcontent']['author'] = $this->authorModel->getAllAuthors();
            $this->data['subcontent']['publisher'] = $this->publisherModel->getAllPublishers();
            $this->data['subcontent']['action'] = '/quan-tri/books/them-sach';

            $this->data['title'] = 'Create Book';
            $this->data['content'] = 'admin/edit-book';

            $this->view('layouts/admin_layout', $this->data);
        }

    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param $id
     * @return void
     */
    public function editBook($id): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataReq = [
                'isbn_code' => trim($_POST['code_isbn']),
                'book_name' => trim($_POST['book_name']),
                'book_title' => trim($_POST['book_title']),
                'author_id' => trim($_POST['author']),
                'publisher_id' => trim($_POST['publisher']),
                'category' => $_POST['category'],
                'year_published' => trim($_POST['year_published']),
                'quantity' => trim($_POST['quantity']),
                'price' => trim($_POST['price']),
                'book_description' => trim($_POST['book_description']),
            ];

            $checkImg = $this->processImg('img_book');
            if ($checkImg['status'] == 'error') {
                echo json_encode([
                    'status' => 'error',
                    'message' => $checkImg['message']
                ]);
                return;
            }

            // Xóa ảnh cũ
            $oldImg = $this->bookModel->getBookById($id)['img'];
            if ($oldImg != '' && $oldImg != 'uploads/no-image.png' && $checkImg['status'] === 'success') {
                unlink($oldImg);
            }

            $dataReq['img'] = $checkImg['path'];

            $dataUpdateBook  = [
                'isbn_code' => $dataReq['isbn_code'],
                'book_name' => $dataReq['book_name'],
                'book_title' => $dataReq['book_title'],
                'book_description' => $dataReq['book_description'],
                'author_id' => $dataReq['author_id'],
                'publisher_id' => $dataReq['publisher_id'],
                'year_published' => $dataReq['year_published'],
                'quantity' => $dataReq['quantity'],
                'price' => $dataReq['price'],
                'img' => $dataReq['img'],
            ];

            $where = ['id' => $id];

            $rs = $this->bookModel->Update($dataUpdateBook, $where);

            if ($rs) {
                $this->bookCategoriesModel->deleteBookCategories($id);
                foreach ($dataReq['category'] as $category) {
                    $dataInsertCategory = [
                        'book_id' => $id,
                        'category_id' => $category
                    ];

                    $this->bookCategoriesModel->insertBookCategories($dataInsertCategory);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Cập nhật sách thành công'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Cập nhật sách thất bại'
                ]);
                return;
            }
        } else {
            $book = $this->bookModel->getBookById($id);
            $this->data['headercontent']['tab'] = 'books';
            $this->data['subcontent']['title'] = 'Edit Book';
            $this->data['subcontent']['book'] = $book;
            $this->data['subcontent']['category'] = $this->categoryModel->getAll('*', 'all');
            $this->data['subcontent']['author'] = $this->authorModel->getAllAuthors();
            $this->data['subcontent']['publisher'] = $this->publisherModel->getAllPublishers();
            $this->data['subcontent']['action'] = '/quan-tri/books/sua-sach/' . $id;

            $this->data['title'] = 'Edit Book';
            $this->data['content'] = 'admin/edit-book';

            $this->view('layouts/admin_layout', $this->data);
        }

    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/16
     * @param $page
     * @return void
     */
    public function search($page = 1): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $conditions = [];

            if (isset($_GET['category']) && $_GET['category'] != '') {
                $conditions['categories'] = implode(' , ', $_GET['category']);
            }

            if (isset($_GET['book_name']) && $_GET['book_name'] != '') {
                $conditions['book_name'] = $_GET['book_name'];
            }

            if (isset($_GET['author']) && $_GET['author'] != '') {
                $conditions['author_id'] = $_GET['author'];
            }

            if (isset($_GET['publisher']) && $_GET['publisher'] != '') {
                $conditions['publisher_id'] = $_GET['publisher'];
            }

            if (isset($_GET['code_isbn']) && $_GET['code_isbn'] != '') {
                $conditions['isbn_code'] = $_GET['code_isbn'];
            }

            if (isset($_GET['year_published']) && $_GET['year_published'] != '') {
                $conditions['year_published'] = $_GET['year_published'];
            }

            $books = $this->bookModel->searchBooks($conditions, $page);

            $this->data['headercontent']['tab'] = 'books';
            $this->data['subcontent']['books'] = $books['data'];
            $this->data['subcontent']['categories'] = $this->categoryModel->getAll('*', 'all');
            $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
            $this->data['subcontent']['publishers'] = $this->publisherModel->getAllPublishers();
            $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($books['total']);
            $this->data['subcontent']['page'] = $page;
            $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/books/search/trang-';
            $this->data['subcontent']['param_string'] = $this->bookModel->createParamString($conditions);
            $this->data['title'] = 'Books';
            $this->data['content'] = 'admin/books';

            $this->view('layouts/admin_layout', $this->data);
        }
    }

    /***
     * @author Phan Đình Phú
     * @param $id
     * @return void
     * @since 2024/10/16
     */
    public function deleteBook($id): void
    {
        $rs = $this->bookModel->Delete(['id' => $id]);

        if ($rs) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Xóa sách thành công'
            ]);
        }

        echo json_encode([
            'status' => 'error',
            'message' => 'Xóa sách thất bại'
        ]);
    }
}