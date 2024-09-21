<?php

class Home extends Controller
{
    private mixed $examModel;
    private array $data = [];

    public function __construct()
    {
        // do something
        $this->examModel = $this->model('ExamModal');
    }

    public function index(): void
    {
        $this->data['subcontent'][''] = [];


        $this->data['title'] = 'Client Home';
        $this->data['content'] = 'client/home';
        $this->view('layouts/client_layout', $this->data);
    }
}