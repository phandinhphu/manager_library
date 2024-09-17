<?php

class Home extends Controller
{
    public function index()
    {
        $data['title'] = 'Client Home';
        $this->view('client/templates/header', $data);
        $this->view('client/home/index', $data);
        $this->view('client/templates/footer', $data);
    }
}