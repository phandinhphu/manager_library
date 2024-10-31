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
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        $this->data['subcontent'] = [];
        $this->data['headercontent']['tab'] = 'dashboard';
        $this->data['title'] = 'Admin Home';
        $this->data['content'] = 'admin/home';
        $this->view('layouts/admin_layout', $this->data);
    }
}