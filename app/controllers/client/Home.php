<?php

class Home extends Controller
{
    private mixed $bookModel;
    private array $data = [];

    public function __construct()
    {
        // do something
        $this->bookModel = $this->model('BookModel');
    }

    public function index(): void
    {
        $newbooks = $this->bookModel->getNewBooks();
        $this->data['headercontent']['tab'] = 'trang-chu';
        $this->data['subcontent']['newbooks'] = $newbooks;
        $this->data['subcontent']['mostlikedbooks'] = $this->bookModel->getMostLikedBooks();
        $this->data['subcontent']['mostdislikedbooks'] = $this->bookModel->getMostDislikedBooks();
        $this->data['title'] = 'Client Home';
        $this->data['content'] = 'client/home';
        $this->view('layouts/client_layout', $this->data);
    }
}