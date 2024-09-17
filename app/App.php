<?php
class App {
    private $__namespace, $__controller, $__method, $__params, $__route;

    public function __construct() {
        global $routes;

        $this->__route = new Route;
        $this->__namespace = $routes['default_namespace'] ?? 'Client';
        $this->__controller = $routes['default_controller'] ?? 'Home';
        $this->__method = 'index';
        $this->__params = [];

        $this->handleUrl();
    }

    public function getUrl() {
        return $_SERVER['QUERY_STRING'] ?? '/';
    }

    public function handleUrl(): void
    {
        $url = $this->getUrl();

        $url = $this->__route->handleRoute($url);

        $urlArr = array_values(array_filter(explode('/', $url)));

        $checkUrl = '';
        if (!empty($urlArr)) {
            foreach ($urlArr as $key => $value) {
                $checkUrl .= '/' . $value;
                $fileArr = explode('/', trim($checkUrl, '/'));
                $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
                $file = implode('/', $fileArr);

                if (!empty($urlArr[$key - 1])) {
                    unset($urlArr[$key - 1]);
                }

                if (file_exists('app/controllers' . $checkUrl . '.php')) {
                    $checkUrl = $file;
                    break;
                }
            }

            $urlArr = array_values($urlArr);
        } else {
            $checkUrl = $this->__namespace . '/' . $this->__controller;
        }

        if (isset($urlArr[0])) {
            $this->__controller = ucfirst($urlArr[0]);
            unset($urlArr[0]);
        } else {
            $this->__controller = ucfirst($this->__controller);
        }

        if (file_exists('app/controllers/' . $checkUrl . '.php')) {
            require_once 'app/controllers/' . $checkUrl . '.php';

            if (class_exists($this->__controller)) {
                $this->__controller = new $this->__controller;

                if (isset($urlArr[1])) {
                    $this->__method = $urlArr[1];
                    unset($urlArr[1]);
                }

                if (method_exists($this->__controller, $this->__method)) {
                    $this->__params = $urlArr ? array_values($urlArr) : [];

                    call_user_func_array([$this->__controller, $this->__method], $this->__params);
                } else {
                    $this->error();
                }
            } else {
                $this->error();
            }
        } else {
            $this->error();
        }
    }

    public function error($error = '404'): void
    {
        require_once 'app/errors/' . $error . '.php';
    }
}