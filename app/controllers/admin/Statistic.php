<?php

class Statistic extends Controller
{
    private mixed $bookModel;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
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
}