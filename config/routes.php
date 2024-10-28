<?php
$routes['default_namespace'] = 'client';
$routes['default_controller'] = 'home';
/*
 *  Đường dẫn ảo => Đường dẫn thật
 * */

// Admin
$routes['quan-tri/dang-nhap'] = 'admin/auth/login';
$routes['quan-tri/dang-ky'] = 'admin/auth/register';
$routes['quan-tri/dang-xuat'] = 'admin/auth/logout';
$routes['quan-tri/books/them-sach'] = 'admin/books/addBook';
$routes['quan-tri/books/delete-book/(\d+)'] = 'admin/books/deleteBook/$1';
$routes['quan-tri/books/search/trang-(\d+).html'] = 'admin/books/search/$1';
$routes['quan-tri/books/search'] = 'admin/books/search';
$routes['quan-tri/books/sua-sach/(\d+)'] = 'admin/books/editBook/$1';
$routes['quan-tri/books/trang-(\d+).html'] = 'admin/books/index/$1';
$routes['quan-tri/books'] = 'admin/books';
$routes['quan-tri/dashboard'] = 'admin/home';
$routes['quan-tri'] = 'admin/home';
// ...

// Client vidu : http://localhost/manager_library/trang-chu
$routes['dang-nhap'] = 'client/auth/login';
$routes['dang-ky'] = 'client/auth/register';
$routes['dang-xuat'] = 'client/auth/logout';
$routes['quen-mat-khau'] = 'client/auth/forgotPassword';
$routes['reset-password'] = 'client/auth/resetPassword';
$routes['kich-hoat-tai-khoan'] = 'client/auth/activeAccount';
$routes['trang-chu'] = 'client/home';
$routes['tra-cuu/trang-(\d+).html'] = 'client/tracuu/index/$1';
$routes['tra-cuu'] = 'client/tracuu';
$routes['sach/yeu-thich/(\d+)'] = 'client/books/like/$1';
$routes['sach/khong-thich/(\d+)'] = 'client/books/dislike/$1';
$routes['sach/chi-tiet/(\d+)'] = 'client/books/detail/$1';
$routes['danh-sach-mong-muon'] = 'client/books/wishlist';
// ...