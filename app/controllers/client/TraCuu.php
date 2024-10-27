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
        $books = $this->bookModel->getBooksByPage($page);
        $this->data['headercontent']['tab'] = 'tra-cuu';
        $this->data['subcontent']['books'] = $books['data'];
        $this->data['subcontent']['categories'] = $this->categoryModel->getAll('*', 'all');
        $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
        $this->data['subcontent']['publishers'] = $this->publisherModel->getAllPublishers();
        $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($books['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/tra-cuu/trang-';

        $this->data['title'] = 'Tra cứu';
        $this->data['content'] = 'client/tracuu';

        $this->view('layouts/client_layout', $this->data);
    }
}