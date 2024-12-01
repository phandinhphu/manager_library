<?php
const _DIR_ROOT = __DIR__;

// Xử lý http root
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $http = 'https://' . $_SERVER['HTTP_HOST'];
} else {
    $http = 'http://' . $_SERVER['HTTP_HOST'];
}

$dir_root = str_replace('\\', '/', _DIR_ROOT);
$folder = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir_root);

$web_root = $http . $folder;

define('WEB_ROOT', $_ENV['WEB_ROOT'] ?? $web_root);

$configs = scandir('config');
foreach ($configs as $config) {
    if ($config == '.' || $config == '..') {
        continue;
    }
    require_once 'config/' . $config;
}

require_once 'core/Connection.php';
require_once 'core/Database.php';
require_once 'core/Route.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'app/App.php';
$app = new App();