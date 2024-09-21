<?php
$routes['default_namespace'] = 'client';
$routes['default_controller'] = 'home';
/*
 *  Đường dẫn ảo => Đường dẫn thật
 * */

// Client vidu : http://localhost/manager_library/trang-chu
$routes['dang-nhap'] = 'client/auth/login';
$routes['dang-ky'] = 'client/auth/register';
$routes['trang-chu'] = 'client/home';
$routes['tra-cuu/trang-(/+d).html'] = 'client/tracuu/index/$1';
$routes['tra-cuu'] = 'client/tracuu';
// ...

// Admin
$routes['quan-tri/dashboard'] = 'admin/home';
// ...