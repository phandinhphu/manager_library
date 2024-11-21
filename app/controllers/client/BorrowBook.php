<?php
class BorrowBook extends Controller {

    private mixed $user_id;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->borrowBookModel = $this->model('BorrowBookModel');
    }

    public function index($page = 1): void
    {
        if (!isset($_SESSION['user']['user_id'])) {
            header('Location: ' . WEB_ROOT . '/dang-nhap');
            exit();
        }
        $this->user_id = $_SESSION['user']['user_id'];
        $borrowed_books = $this->borrowBookModel->getByPage(["user_id" => $this->user_id], $page);

        $this->data['headercontent']['tab'] = 'muon-sach';
        $this->data['subcontent']['borrowed_books'] = $borrowed_books['data'];
        $this->data['subcontent']['total_pages'] = $this->borrowBookModel->getTotalPage($borrowed_books['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/muon-sach/trang-';

        $this->data['title'] = 'Sách mượn';
        $this->data['content'] = 'client/borrowbook';
        $this->view('layouts/client_layout', $this->data);
    }

    /**
     * Tìm kiếm sách mượn
     * @param int $page
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-13
     */
    public function search(int $page = 1): void
    {
        if (!isset($_SESSION['user']['user_id'])) {
            header('Location: ' . WEB_ROOT . '/dang-nhap');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $where = [];

            $where['user_id'] = $_SESSION['user']['user_id'];

            if (isset($_GET['isbn_code']) && $_GET['isbn_code'] != '') {
                $where['isbn_code'] = $_GET['isbn_code'];
            }

            if (isset($_GET['book_name']) && $_GET['book_name'] != '') {
                $where['book_name'] = $_GET['book_name'];
            }

            if (isset($_GET['author_name']) && $_GET['author_name'] != '') {
                $where['author_name'] = $_GET['author_name'];
            }

            if (isset($_GET['status']) && $_GET['status'] != '') {
                $status = $_GET['status'];
            }

            if (isset($_GET['sort']) && $_GET['sort'] != '') {
                $orderBy = $_GET['sort'];
            }

            $borrowed_books = $this->borrowBookModel->search($where, $orderBy, $status, $page);

            $this->data['headercontent']['tab'] = 'muon-sach';
            $this->data['subcontent']['borrowed_books'] = $borrowed_books['data'];
            $this->data['subcontent']['total_pages'] = $this->borrowBookModel->getTotalPage($borrowed_books['total'] ?? 0);
            $this->data['subcontent']['page'] = $page;
            $this->data['subcontent']['url_page'] = WEB_ROOT . '/muon-sach/trang-';

            $this->data['title'] = 'Tìm kiếm sách mượn';
            $this->data['content'] = 'client/borrowbook';
            $this->view('layouts/client_layout', $this->data);
        } else {
            header('Location: ' . WEB_ROOT . '/muon-sach');
            exit();
        }
    }
}