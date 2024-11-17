<?php

class Request extends Controller
{
    private mixed $requestModel;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->requestModel = $this->model('RequestModel');
        $this->borrowBookModel = $this->model('BorrowBook');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/5
     * @param int $page
     * @return void
     */
    public function index($page = 1): void
    {
        if (isset($_GET['user_name'])) {
            $requests = $this->requestModel->getByCondition(['user_name' => $_GET['user_name']], '*', $page);
        } else {
            $requests = $this->requestModel->getAll('r.*, b.book_name, u.user_name, u.id as user_id', $page);
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/5
     * @param int $id
     * @param int $page
     * @return void
     */
    public function accepted($id, $page): void
    {
        $request = $this->requestModel->getById($id)[0];

        $dataIns = [
            'user_id' => $request['user_id'],
            'book_id' => $request['book_id'],
            'staff_id' => $_SESSION['admin']['user_id'],
            'borrow_date' => $request['create_date'],
            'due_date' => $request['expire_date'],
            'return_date' => null,
            'book_status' => null,
            'quantity' => $request['quantity'],
        ];

        $rs = $this->borrowBookModel->Add($dataIns);

        if ($rs) {
            $this->requestModel->Delete(['id' => $id]);
            $requests = $this->requestModel->getAll('r.*, b.book_name, u.user_name', $page);
            echo json_encode([
                'status' => 'success',
                'message' => 'Duyệt yêu cầu thành công',
                'data' => $requests['data'],
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau',
            ]);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/5
     * @param int $id
     * @param int $page
     * @return void
     */
    public function denied($id, $page): void
    {
        $rs = $this->requestModel->Delete(['id' => $id]);

        if ($rs) {
            $requests = $this->requestModel->getAll('r.*, b.book_name, u.user_name', $page);
            echo json_encode([
                'status' => 'success',
                'message' => 'Từ chối yêu cầu thành công',
                'data' => $requests['data'],
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau',
            ]);
        }
    }
}