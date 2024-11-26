<?php

class Statistic extends Controller
{
    private mixed $bookModel;
    private mixed $userModel;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
        $this->userModel = $this->model('UserModel');
        $this->borrowBookModel = $this->model('BorrowBookModel');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/21
     * @param int $page
     * @return void
     */
    public function book($page = 1): void
    {
        $condition = [];

        if (isset($_GET['book_name'])) {
            $condition['book_name'] = $_GET['book_name'];
        }

        $books = $this->bookModel->statistic($condition, $page);

        $this->data['headercontent']['tab'] = 'statistics';
        $this->data['subcontent']['title'] = 'Thống kê sách';
        $this->data['subcontent']['total_books'] = $this->bookModel->getAllBooks()['total'];
        $this->data['subcontent']['books'] = $books['data'];
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($books['total']);
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/thong-ke-sach/trang-';
        $this->data['subcontent']['param_string'] = isset($_GET['book_name']) ? 'book_name=' . $_GET['book_name'] : '';

        $this->data['title'] = 'Thống kê sách';
        $this->data['content'] = 'admin/statistics/book';

        $this->view('layouts/admin_layout', $this->data);

    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/21
     * @param int $year
     * @return void
     */
    public function amountBorrowBook($year = ''): void
    {
        if ($year == '') {
            $year = date('Y');
        }

        $data = $this->borrowBookModel->getAmountBorrowedOverYear($year);

        echo json_encode($data);
    }

    public function exportFileExcelAllBooks(): void
    {
        $this->bookModel->exportExcelAllBooks();
    }

    public function exportFileExcelBook($bookId): void
    {
        $this->bookModel->exportExcelBook($bookId);
    }

    /**
     * Hiển thị trang thống kê độc giả
     * @param mixed $page
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function reader($page = 1): void 
    {
        $condition = [];
        if (isset($_GET['reader_name'])) {
            $condition['reader_name'] = $_GET['reader_name'];
        }
        
        $readers = $this->userModel->statistic($condition, $page);

        $this->data['headercontent']['tab'] = 'statistic';
        $this->data['subcontent']['title'] = 'Thống kê độc giả';
        $this->data['subcontent']['total_reader'] = $readers['total'];
        $this->data['subcontent']['readers'] = $readers['data'];
        $this->data['subcontent']['total_pages'] = $this->userModel->getTotalPage($readers['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/thong-ke-doc-gia/trang-';
        $this->data['subcontent']['script'] = 'statistic';
        $this->data['subcontent']['param_string'] = isset($_GET['reader_name']) ? 'reader_name=' . $_GET['reader_name'] : '';

        $this->data['title'] = 'Thống kê độc giả';
        $this->data['content'] = 'admin/statistics/reader';

        $this->view('layouts/admin_layout', $this->data);
    }

    /**
     * Lấy thông tin chi tiết danh sách mượn của độc giả
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function getDetailReader(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
                $row = $this->borrowBookModel->getBorrowBookByUserId($id);
                if($row) {
                    header('Content-type: application/json');
                    echo json_encode($row);
                    return;
                }
            } else {
                $this->response(0, 'Không lấy được dữ liệu vui lòng thử lại!');
            }
        }
    }

    public function getReaderStats(): void
    {
        try {
            $data = [
                'reader' => $this->userModel->getReaderStats(),
                'fine'   => $this->userModel->getFineStats(),
            ];
            header('Content-type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    

    public function exportFileExcelAllReaders(): void
    {
        $this->userModel->exportExcelAllReaders();
    }

    public function exportFileExcelReader($userId): void
    {
        $this->borrowBookModel->exportExcelReader($userId);
    }
}