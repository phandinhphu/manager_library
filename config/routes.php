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
$routes['quan-tri/yeu-cau-muon-sach/trang-(\d+).html'] = 'request/$1';
$routes['quan-tri/yeu-cau-muon-sach'] = 'request';
$routes['quan-tri/quan-ly-tai-khoan/get-account/(\d+)'] = 'admin/account/getAccount/$1';
$routes['quan-tri/quan-ly-tai-khoan/them-tai-khoan'] = 'admin/account/addAccount';
$routes['quan-tri/quan-ly-tai-khoan/sua-tai-khoan/(\d+)'] = 'admin/account/editAccount/$1';
$routes['quan-tri/quan-ly-tai-khoan/xoa-tai-khoan/(\d+)'] = 'admin/account/deleteAccount/$1';
$routes['quan-tri/quan-ly-tai-khoan/trang-(\d+).html'] = 'admin/account/$1';
$routes['quan-tri/quan-ly-tai-khoan'] = 'admin/account';
$routes['quan-tri/danh-sach-sach-muon/trang-(\d+).html'] = 'admin/borrowbook/index/$1';
$routes['quan-tri/danh-sach-sach-muon'] = 'admin/borrowbook';
$routes['quan-tri/thong-ke-sach/so-luong-muon-sach/(\d+)'] = 'admin/statistic/amountBorrowBook/$1';
$routes['quan-tri/thong-ke-sach/so-luong-muon-sach'] = 'admin/statistic/amountBorrowBook';
$routes['quan-tri/thong-ke-sach/trang-(\d+).html'] = 'admin/statistic/book/$1';
$routes['quan-tri/thong-ke-sach'] = 'admin/statistic/book';

// Category
$routes['quan-tri/quan-ly-the-loai/trang-(\d+).html'] = 'admin/category/index/$1';
$routes['quan-tri/quan-ly-the-loai/tim-kiem/trang-(\d+).html'] = 'admin/category/search/$1';
$routes['quan-tri/quan-ly-the-loai/tim-kiem'] = 'admin/category/search';
$routes['quan-tri/quan-ly-the-loai'] = 'admin/category';


// Publisher
$routes['quan-tri/quan-ly-nha-xuat-ban/trang-(\d+).html'] = 'admin/publisher/index/$1';
$routes['quan-tri/quan-ly-nha-xuat-ban'] = 'admin/publisher';


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
$routes['tra-cuu/trang-(\d+).html'] = 'client/tracuu/index/$1';
$routes['tra-cuu'] = 'client/tracuu';
$routes['sach/yeu-thich/(\d+)'] = 'client/books/like/$1';
$routes['sach/khong-thich/(\d+)'] = 'client/books/dislike/$1';
$routes['sach/chi-tiet/(\d+)'] = 'client/books/detail/$1';
$routes['danh-sach-mong-muon/trang-(\d+).html'] = 'client/books/wishlist/$1';
$routes['danh-sach-mong-muon'] = 'client/books/wishlist';
$routes['add-request'] = 'request/addRequest';
$routes['thong-tin-ca-nhan'] = 'client/auth/profile';
$routes['doi-mat-khau'] = 'client/auth/changePassword';

// Borrow Book
$routes['muon-sach/trang-(\d+).html'] = 'client/borrowbook/index/$1';
$routes['muon-sach/tim-kiem/trang-(\d+).html'] = 'client/borrowbook/search/$1';
$routes['muon-sach/tim-kiem'] = 'client/borrowbook/search';
$routes['muon-sach'] = 'client/borrowbook';

$routes['trang-chu'] = 'client/home';
// ...