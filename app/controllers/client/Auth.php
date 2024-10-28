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
        $this->authModel = $this->model('UserModel');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['user']);
        header('location: ' . WEB_ROOT . '/dang-nhap');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @return void
     */
    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('location: ' . WEB_ROOT . '/trang-chu');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $dataReq = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $loggedInUser = $this->authModel->login($dataReq['email'], $dataReq['password']);

            if ($loggedInUser) {
                $this->createUserSession($loggedInUser);
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
        $this->data['content'] = 'client/auth/login';

        $this->view('layouts/client_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @return void
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataReq = [
                'email' => trim($_POST['email']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'phone' => trim($_POST['phone']),
                'cccd' => trim($_POST['cccd']),
                'address' => trim($_POST['address']),
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
            if ($this->authModel->getUser(['user_name' => $dataReq['username']])) {
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
            $activeToken = md5(uniqid(rand(), true));
            
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
                'active_token' => $activeToken
            ];
            
            $rs = $this->authModel->Add($dataIns);

            if ($rs) {
                $resSendEmail = $this->sendMail(
                    $dataReq['email'],
                    'Kích hoạt tài khoản',
                    'Click vào link sau để kích hoạt tài khoản: ' . WEB_ROOT . '/kich-hoat-tai-khoan?token=' . $activeToken
                );

                if ($resSendEmail) {
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
     * @since 2024/10/14
     * @return void
     */
    public function activeAccount(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = $_GET['token'];
            $user = $this->authModel->getUser(['active_token' => $token]);

            if ($user) {
                $this->authModel->updateActive($user['id']);
                echo '
                    <h1>Kích hoạt tài khoản thành công</h1>
                    <a href="' . WEB_ROOT . '/dang-nhap">Đăng nhập</a>
                ';
            } else {
                echo '
                    <h1>Token không hợp lệ</h1>
                ';
            }
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/28
     * @return void
     */
    public function forgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $user = $this->authModel->getUser(['email' => $email]);

            if ($user) {
                $token = md5(uniqid(rand(), true));

                $rs = $this->sendMail(
                    $email,
                    'Quên mật khẩu',
                    'Click vào link sau để đặt lại mật khẩu: ' . WEB_ROOT . '/reset-password?token=' . $token
                );

                if ($rs) {
                    $this->authModel->Update(['forgot_token' => $token], ['email' => $email]);
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Vui lòng kiểm tra email để đặt lại mật khẩu'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email không tồn tại'
                ]);
            }
        } else {
            $this->data['title'] = 'Forgot Password';
            $this->data['content'] = 'client/auth/forgotpassword';

            $this->view('layouts/client_layout', $this->data);
        }

    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/28
     * @return void
     */
    public function resetPassword(): void
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = $this->authModel->getUser(['forgot_token' => $token]);

            if ($user) {
                $this->data['subcontent']['token'] = $token;
                $this->data['title'] = 'Đặt lại mật khẩu';
                $this->data['content'] = 'client/auth/resetpassword';

                $this->view('layouts/client_layout', $this->data);
            } else {
                echo '
                    <h1>Token không hợp lệ</h1>
                ';
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $token = $_POST['token'];
                $password = $_POST['password'];

                $user = $this->authModel->getUser(['forgot_token' => $token]);

                if ($user) {
                    $rs = $this->authModel->Update([
                        'pass_word' => password_hash($password, PASSWORD_DEFAULT),
                        'update_at' => date('Y-m-d H:i:s')
                    ], ['forgot_token' => $token]);

                    if ($rs) {
                        $this->authModel->Update(['forgot_token' => null], ['email' => $user['email']]);

                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Đặt lại mật khẩu thành công'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Token không hợp lệ'
                    ]);
                }
            } else {
                echo '
                    <h1>Không tìm thấy token đặt lại mật khẩu</h1>
                ';
            }
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @param $loggedInUser
     * @return void
     */
    private function createUserSession($loggedInUser): void
    {
        $_SESSION['user']['user_id'] = $loggedInUser['id'];
        $_SESSION['user']['email'] = $loggedInUser['email'];
        $_SESSION['user']['name'] = $loggedInUser['user_name'];
        $_SESSION['user']['avatar'] = $loggedInUser['avatar'];
        header('location: ' . WEB_ROOT . '/trang-chu');
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

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @param $to
     * @param $subject
     * @param $message
     * @return bool
     */
    private function sendMail($to, $subject, $message): bool
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'phuphandinh2004@gmail.com';                     //SMTP username
            $mail->Password   = 'tebd hrzo dujx nvcr';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('phuphandinh2004@gmail.com', 'Phan Dinh Phu');
            $mail->addAddress($to);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}