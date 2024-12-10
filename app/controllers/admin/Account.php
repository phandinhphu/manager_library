<?php

class Account extends Controller
{
    private mixed $accountModel;
    private array $data = [];

    public function __construct()
    {
        $this->accountModel = $this->model('UserModel');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param int $page
     * @return void
     */
    public function index($page = 1): void
    {
        if (isset($_GET['user_name'])) {
            $accounts = $this->accountModel->getUserByName($_GET['user_name'], $page);
        } else {
            $accounts = $this->accountModel->getAll('*', $page);
        }
        $this->data['headercontent']['tab'] = 'reader';
        $this->data['subcontent']['accounts'] = $accounts['data'];
        $this->data['subcontent']['total_pages'] = $this->accountModel->getTotalPage($accounts['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-tai-khoan/trang-';
        $this->data['subcontent']['param_string'] = isset($_GET['user_name']) ? 'user_name=' . $_GET['user_name'] : '';
        

        $this->data['title'] = 'Quản lý tài khoản';
        $this->data['content'] = 'admin/account';

        $this->view('layouts/admin_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param int $id
     * @return void
     */
    public function getAccount($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $account = $this->accountModel->getUser(['id' => $id]);

            echo json_encode($account);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @return void
     */
    public function addAccount(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataReq = [
                'email' => trim($_POST['email']),
                'username' => trim($_POST['user_name']),
                'password' => trim($_POST['password']),
                'phone' => trim($_POST['phone']),
                'cccd' => trim($_POST['cccd']),
                'address' => trim($_POST['address']),
            ];

            // Validate email
            if ($this->accountModel->getUser(['email' => $dataReq['email']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email đã tồn tại'
                ]);
                return;
            }

            // Validate username
            if ($this->accountModel->getUser(['user_name' => $dataReq['username']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tên người dùng đã tồn tại'
                ]);
                return;
            }

            // Validate cccd
            if ($this->accountModel->getUser(['cccd' => $dataReq['cccd']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Số căng cước công dân đã tồn tại'
                ]);
                return;
            }

            $checkAvatar = $this->processImg('avatar');

            if ($checkAvatar['status'] === 'error') {
                echo json_encode([
                    'status' => 'error',
                    'message' => $checkAvatar['message']
                ]);
                return;
            }

            $dataReq['avatar'] = $checkAvatar['path'];
            $dataReq['password'] = password_hash($dataReq['password'], PASSWORD_DEFAULT);
            
            $dataIns = [
                'email' => $dataReq['email'],
                'user_name' => $dataReq['username'],
                'pass_word' => $dataReq['password'],
                'phone_number' => $dataReq['phone'],
                'cccd' => $dataReq['cccd'],
                'address' => $dataReq['address'],
                'avatar' => $dataReq['avatar'],
                'create_at' => date('Y-m-d H:i:s'),
                'update_at' => date('Y-m-d H:i:s'),
                'status_account' => 1,
            ];
            
            $result = $this->accountModel->Add($dataIns);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Thêm tài khoản thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Thêm tài khoản thất bại']);
            }
        } else {
            $this->data['headercontent']['tab'] = 'reader';
            $this->data['subcontent']['title'] = 'Thêm tài khoản';
            $this->data['subcontent']['action'] = '/quan-tri/quan-ly-tai-khoan/them-tai-khoan';

            $this->data['title'] = 'Thêm tài khoản';
            $this->data['content'] = 'admin/edit-account';

            $this->view('layouts/admin_layout', $this->data);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param int $id
     * @return void
     */
    public function editAccount($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataReq = [
                'email' => trim($_POST['email']),
                'username' => trim($_POST['user_name']),
                'phone' => trim($_POST['phone']),
                'cccd' => trim($_POST['cccd']),
                'address' => trim($_POST['address']),
            ];

            $account = $this->accountModel->getUser(['id' => $id]);

            // Validate email
            if ($account['email'] !== $dataReq['email'] && $this->accountModel->getUser(['email' => $dataReq['email']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email đã tồn tại'
                ]);
                return;
            }

            // Validate username
            if ($account['user_name'] !== $dataReq['username'] && $this->accountModel->getUser(['user_name' => $dataReq['username']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tên người dùng đã tồn tại'
                ]);
                return;
            }

            // Validate cccd
            if ($account['cccd'] !== $dataReq['cccd'] && $this->accountModel->getUser(['cccd' => $dataReq['cccd']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Số căng cước công dân đã tồn tại'
                ]);
                return;
            }

            $checkAvatar = $this->processImg('avatar');

            if ($checkAvatar['status'] === 'error') {
                echo json_encode([
                    'status' => 'error',
                    'message' => $checkAvatar['message']
                ]);
                return;
            }

            // Xoá ảnh cũ
            if ($account['avatar'] !== $checkAvatar['path'] && $checkAvatar['status'] === 'success') {
                if ($account['avatar'] !== 'uploads/no-image.png') {
                    unlink($account['avatar']);
                }
            }

            $dataReq['avatar'] = $checkAvatar['path'];

            $dataIns = [
                'email' => $dataReq['email'],
                'user_name' => $dataReq['username'],
                'phone_number' => $dataReq['phone'],
                'cccd' => $dataReq['cccd'],
                'address' => $dataReq['address'],
                'avatar' => $dataReq['avatar'],
                'update_at' => date('Y-m-d H:i:s'),
            ];

            $result = $this->accountModel->Update($dataIns, ['id' => $id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật tài khoản thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật tài khoản thất bại']);
            }
        } else {
            $account = $this->accountModel->getUser(['id' => $id]);

            $this->data['headercontent']['tab'] = 'reader';
            $this->data['subcontent']['title'] = 'Sửa tài khoản';
            $this->data['subcontent']['action'] = '/quan-tri/quan-ly-tai-khoan/sua-tai-khoan/' . $id;
            $this->data['subcontent']['account'] = $account;

            $this->data['title'] = 'Sửa tài khoản';
            $this->data['content'] = 'admin/edit-account';

            $this->view('layouts/admin_layout', $this->data);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param int $id
     * @return void
     */
    public function deleteAccount($id): void
    {
        $account = $this->accountModel->getUser(['id' => $id]);

        if ($account) {
            $result = $this->accountModel->Delete(['id' => $id]);

            if ($result) {
                unlink($account['avatar']);
                echo json_encode(['status' => 'success', 'message' => 'Xóa tài khoản thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Xóa tài khoản thất bại']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tài khoản không tồn tại']);
        }
    }
}