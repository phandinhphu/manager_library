<?php

class Home extends Controller
{
    private mixed $bookModel;
    private mixed $fineModel;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
        $this->fineModel = $this->model('FineModel');
        $this->borrowBookModel = $this->model('BorrowBook');
    }

    public function index(): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        $this->data['headercontent']['tab'] = 'dashboard';
        $this->data['subcontent']['title'] = 'Dashboard';
        $this->data['subcontent']['new_imported_books'] = $this->bookModel->getNewImportedBooks();
        $this->data['subcontent']['readers_violated'] = $this->fineModel->getNewReaderViolated();
        $this->data['subcontent']['readers_borrowing'] = $this->borrowBookModel->getNewReaderBorrowing();

        $this->data['title'] = 'Dashboard';
        $this->data['content'] = 'admin/home';
        $this->view('layouts/admin_layout', $this->data);
    }
}