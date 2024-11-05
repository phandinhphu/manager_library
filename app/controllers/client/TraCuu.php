<?php

class TraCuu extends Controller
{
    private mixed $bookModel, $categoryModel, $authorModel, $publisherModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->authorModel = $this->model('AuthorModel');
        $this->publisherModel = $this->model('PublisherModel');
    }

    public function index($page = 1): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

            if ($conditions) {
                $books = $this->bookModel->searchBooks($conditions, $page);
            } else {
                $books = $this->bookModel->getBooksByPage($page);
            }
        }

        $this->data['headercontent']['tab'] = 'tra-cuu';
        $this->data['subcontent']['books'] = $books['data'];
        $this->data['subcontent']['categories'] = $this->categoryModel->getAll('*', 'all');
        $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
        $this->data['subcontent']['publishers'] = $this->publisherModel->getAllPublishers();
        $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($books['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['param_string'] = $this->bookModel->createParamString($conditions);
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/tra-cuu/trang-';

        $this->data['title'] = 'Tra cứu';
        $this->data['content'] = 'client/tracuu';

        $this->view('layouts/client_layout', $this->data);
    }
}