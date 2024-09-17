<?php

class Home extends Controller
{
    private array $data = [];

    public function __construct()
    {
        // do something
    }

    public function index()
    {
        $this->data['title'] = 'Admin Home';
        $this->data['content'] = 'admin/home';
        $this->view('layouts/admin_layout', $this->data);
    }
}