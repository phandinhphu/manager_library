<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Controller {
    public function view($view, $data = []): void
    {
        extract($data);
        if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
            require_once _DIR_ROOT . '/app/views/' . $view . '.php';
        } else {
            die('View does not exist');
        }
    }

    public function model($model) {
        if (file_exists('app/models/' . $model . '.php')) {
            require_once 'app/models/' . $model . '.php';
            if (class_exists($model)) {
                return new $model;
            }
        }
        return null;
    }

    public function redirect($url): void
    {
        header('Location: ' . $url);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @param $folder
     * @return string[]
     */
    public function processImg($folder): array
    {
        if (isset($_FILES['img'])) {
            // Thư mục lưu trữ ảnh
            $target_dir = "uploads/" . $folder . "/";
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Tên File
            $filename = pathinfo($_FILES['img']['name'], PATHINFO_FILENAME);
            // Lấy phần mở rộng của file
            $imageFileType = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            // Đường dẫn file
            $target_file = $target_dir . $filename . '_' . uniqid() . '.' . $imageFileType;

            // Kiểm tra xem file có phải là file ảnh không
            $check = getimagesize($_FILES["img"]["tmp_name"]);
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
            if ($_FILES["img"]["size"] > 5000000) {
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
            if (!move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
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

    /**
     * Hàm dùng để phản hồi kết quả xử lý
     * @param mixed $success
     * @param mixed $message
     * @return void
     * @author Trần Duy Vương
     * @since 2024-10-31
     */
    public function response($success, $message): void
    {
        header('Content-type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/17
     * @param $to
     * @param $subject
     * @param $message
     * @return bool
     */
    public function sendMail($to, $subject, $message): bool
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