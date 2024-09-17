<?php

class Home extends Controller
{
    private array $data = [];

    public function __construct()
    {
        // do something
    }

    public function index(): void
    {
        $this->data['title'] = 'Client Home';
        $this->data['content'] = 'client/home';
        $this->view('layouts/client_layout', $this->data);
    }
}