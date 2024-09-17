<?php
$routes['default_namespace'] = 'client';
$routes['default_controller'] = 'home';
/*
 *  Đường dẫn ảo => Đường dẫn thật
 * */

// Client
$routes['trang-chu'] = 'client/home/index';
// ...

// Admin
$routes['admin/trang-chu'] = 'admin/home/index';
// ...