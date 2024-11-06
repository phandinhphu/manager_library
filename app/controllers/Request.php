<?php

class Request extends Controller
{
    private mixed $requestModel;
    private array $data = [];

    public function __construct()
    {
        $this->requestModel = $this->model('RequestModel');
    }

    public function index($page = 1): void
    {
        if (isset($_GET['user_name'])) {
            $requests = $this->requestModel->getByCondition(['user_name' => $_GET['user_name']], '*', $page);
        } else {
            $requests = $this->requestModel->getAll('*', $page);
        }

        $this->data['headercontent']['tab'] = 'request';
        $this->data['subcontent']['requests'] = $requests['data'];
        $this->data['subcontent']['total_pages'] = $this->requestModel->getTotalPage($requests['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/yeu-cau-muon-sach/trang-';
        $this->data['subcontent']['title'] = 'Danh sách yêu cầu';

        $this->data['content'] = 'admin/request';
        $this->data['title'] = 'Danh sách yêu cầu';

        $this->view('layouts/admin_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/3
     * @return void
     */
    public function addRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $books = json_decode(file_get_contents('php://input'), true);
            $_flag = true;

            foreach ($books as $book) {
                $expire_date = DateTime::createFromFormat('Y-m-d', $book['return_date']);
                $dataIns = [
                    'book_id' => $book['book_id'],
                    'user_id' => $_SESSION['user']['user_id'],
                    'quantity' => $book['quantity'],
                    'expire_date' => $expire_date->format('Y-m-d H:i:s'),
                    'create_date' => date('Y-m-d H:i:s')
                ];

                $rs = $this->requestModel->Add($dataIns);

                if (!$rs) {
                    $_flag = false;
                    break;
                }
            }

            if ($_flag) {
                unset($_SESSION['books']['wishlist']);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Yêu cầu mượn sách thành công'
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
                'message' => 'Phương thức không hợp lệ'
            ]);
        }
    }
}