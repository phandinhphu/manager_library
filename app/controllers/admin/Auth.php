<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth extends Controller
{
    private mixed $authModel;
    private array $data = [];

    public function __construct()
    {
        $this->authModel = $this->model('StaffModel');
    }
    
    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['admin']);
        header('location: ' . WEB_ROOT . '/quan-tri/dashboard');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @return void
     */
    public function login(): void
    {
        if (isset($_SESSION['admin'])) {
            header('location: ' . WEB_ROOT . '/quan-tri/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $dataReq = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $loggedInUser = $this->authModel->login($dataReq['email'], $dataReq['password']);

            if ($loggedInUser) {
                $this->createAdminSession($loggedInUser);
            } else {
                if (!$this->authModel->getUser(['email' => $dataReq['email']])) {
                    $data['email_err'] = 'No user found. Check your email';
                } else {
                    $data['password_err'] = 'Password incorrect';
                }
                $this->data['subcontent']['errors'] = $data;
            }
        }

        $this->data['title'] = 'Login';
        $this->data['content'] = 'admin/auth/login';

        $this->view('layouts/admin_login_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @return void
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataReq = [
                'email' => trim($_POST['email']),
                'staffname' => trim($_POST['staffname']),
                'password' => trim($_POST['password']),
                'phone' => trim($_POST['phone']),
                'cccd' => trim($_POST['cccd']),
            ];

            $checkAvatar = $this->processAvatar();

            if ($checkAvatar['status'] === 'error') {
                echo json_encode([
                    'status' => 'error',
                    'message' => $checkAvatar['message']
                ]);
                return;
            }

            // Validate email
            if ($this->authModel->getUser(['email' => $dataReq['email']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email đã tồn tại'
                ]);
                return;
            }

            // Validate username
            if ($this->authModel->getUser(['staff_name' => $dataReq['staffname']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tên người dùng đã tồn tại'
                ]);
                return;
            }

            // Validate cccd
            if ($this->authModel->getUser(['cccd' => $dataReq['cccd']])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Số căng cước công dân đã tồn tại'
                ]);
                return;
            }

            $dataReq['avatar'] = $checkAvatar['path'];
            $dataReq['password'] = password_hash($dataReq['password'], PASSWORD_DEFAULT);

            $dataIns = [
                'staff_name' => $dataReq['staffname'],
                'pass_word' => $dataReq['password'],
                'phone_number' => $dataReq['phone'],
                'avatar' => $dataReq['avatar'],
                'email' => $dataReq['email'],
                'cccd' => $dataReq['cccd'],
                'create_at' => date('Y-m-d H:i:s'),
                'update_at' => date('Y-m-d H:i:s'),
            ];
            
            $rs = $this->authModel->Add($dataIns);

            if ($rs) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản',
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Đăng ký không thành công. Vui lòng thử lại sau',
                ]);
            }
        } else {
            $this->data['title'] = 'Register';
            $this->data['content'] = 'client/auth/register';

            $this->view('layouts/client_layout', $this->data);
        }

    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @param $loggedInUser
     * @return void
     */
    private function createAdminSession($loggedInUser): void
    {
        $_SESSION['admin']['user_id'] = $loggedInUser['id'];
        $_SESSION['admin']['email'] = $loggedInUser['email'];
        $_SESSION['admin']['name'] = $loggedInUser['staff_name'];
        $_SESSION['admin']['avatar'] = $loggedInUser['avatar'];
        header('location: ' . WEB_ROOT . '/quan-tri/dashboard');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @return string[]
     */
    private function processAvatar(): array
    {
        if (isset($_FILES['avatar'])) {
            // Thư mục lưu trữ ảnh
            $target_dir = "uploads/avatar/";
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Tên File
            $filename = pathinfo($_FILES['avatar']['name'], PATHINFO_FILENAME);
            // Lấy phần mở rộng của file
            $imageFileType = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            // Đường dẫn file
            $target_file = $target_dir . $filename . '_' . uniqid() . '.' . $imageFileType;

            // Kiểm tra xem file có phải là file ảnh không
            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
            if (!$check) {
                return [
                    'status' => 'error',
                    'message' => "File không phải là file ảnh"
                ];
            }

            // Kiểm tra file có tồn tại không
            if (file_exists($target_file)) {
                return [
                    'status' => 'error',
                    'message' => "File đã tồn tại"
                ];
            }

            // Kiểm tra kích thước file
            if ($_FILES["avatar"]["size"] > 5000000) {
                return [
                    'status' => 'error',
                    'message' => "File quá lớn"
                ];
            }

            // Kiểm tra định dạng file
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
                return [
                    'status' => 'error',
                    'message' => "Chỉ chấp nhận file JPG, JPEG, PNG & WEBP"
                ];
            }

            // Upload file
            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                return [
                    'status' => 'error',
                    'message' => "Có lỗi xảy ra khi upload file"
                ];
            }

            return [
                'status' => 'success',
                'path' => $target_file
            ];
        }

        return [
            'status' => 'success',
            'path' => 'uploads/no-image.png'
        ];
    }
}