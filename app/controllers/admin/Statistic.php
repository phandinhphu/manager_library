<?php

class Statistic extends Controller
{
    private mixed $bookModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
    }

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
}