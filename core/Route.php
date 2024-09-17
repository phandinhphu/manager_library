<?php
class Route
{
    public function __construct() {
    }

    public function handleRoute($url): array|string|null
    {
        global $routes;
        unset($routes['default_controller']);
        $url = trim($url, '/');
        if (!empty($routes)) {
            foreach ($routes as $key => $value) {
                if (preg_match('~'.$key.'~is', $url)) {
                    $url = preg_replace('~'.$key.'~is', $value, $url);
                }
            }
        }

        $url = explode('&', $url);
        return $url[0];
    }
}