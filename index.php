<?php
session_start();
require_once './vendor/autoload.php';

// Sử dụng dotenv để load các biến môi trường từ file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.development');
$dotenv->load();

require_once 'bootstrap.php';