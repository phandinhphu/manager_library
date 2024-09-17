<?php
class Controller {
    public function view($view, $data = []): void
    {
        extract($data);
        if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
            require_once _DIR_ROOT . '/app/views/' . $view . '.php';
        } else {
            die('View does not exist');
        }
    }

    public function model($model) {
        if (file_exists('app/models/' . $model . '.php')) {
            require_once 'app/models/' . $model . '.php';
            if (class_exists($model)) {
                return new $model;
            }
        }
        return null;
    }

    public function redirect($url): void
    {
        header('Location: ' . $url);
    }
}