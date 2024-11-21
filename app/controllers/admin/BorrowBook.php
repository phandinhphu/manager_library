<?php

class BorrowBook extends Controller
{
    private mixed $borrowBookModel;
    private mixed $fineModel;
    private mixed $userModel;
    private array $data = [];

    public function __construct()
    {
        $this->borrowBookModel = $this->model('BorrowBookModel');
        $this->fineModel = $this->model('FineModel');
        $this->userModel = $this->model('UserModel');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/18
     * @param int $page
     */
    public function index($page = 1): void
    {
        if (isset($_GET['user_name'])) {
            $borrowBooks = $this->borrowBookModel->getByCondition(
                ['user_name' => $_GET['user_name']],
                'r.id, u.id as user_id, u.user_name, b.book_name, r.borrow_date, r.due_date',
                $page
            );
        } else {
            $borrowBooks = $this->borrowBookModel->getAll(
                'r.id, u.id as user_id, u.user_name, b.book_name, r.borrow_date, r.due_date', 
                $page
            );
        }

        $this->data['headercontent']['tab'] = 'reader';
        $this->data['subcontent']['title'] = 'Danh sách sách mượn';
        $this->data['subcontent']['borrowBooks'] = $borrowBooks['data'];
        $this->data['subcontent']['total_pages'] = $this->borrowBookModel->getTotalPage($borrowBooks['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/danh-sach-sach-muon/trang-';
        $this->data['subcontent']['param_string'] = isset($_GET['user_name']) ? '?user_name=' . $_GET['user_name'] : '';

        $this->data['title'] = 'Danh sách sách mượn';
        $this->data['content'] = 'admin/borrowbook';

        $this->view('layouts/admin_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/18
     * @param int $id
     */
    public function getById($id): void
    {
        $borrowBooks = $this->borrowBookModel->getInfoReturnBook($id);

        echo json_encode($borrowBooks);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/18
     * @param int $userId
     * @param string $subject
     * @param string $message
     * return void
     */
    public function sendWarningEmail($userId, $subject = '', $message = ''): void
    {
        if ($subject === '' || $message === '') {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đủ thông tin']);
            return;
        }

        $user = $this->userModel->getUser(['id' => $userId]);

        $rs = $this->sendMail($user['email'], $subject, $message);

        if ($rs) {
            echo json_encode(['status' => 'success', 'message' => 'Gửi email thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gửi email thất bại']);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/18
     * return void
     */
    public function confirmReturnBook(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $rsUpdate = $this->borrowBookModel->Update(
                ['return_date' => $data['return_date'], 'book_status' => $data['book_status']],
                ['id' => $data['id']]
            );

            $rsAdd = $this->fineModel->Add([
                'borrow_id' => $data['id'],
                'fine_amount' => $data['fine_amount'],
                'days_overdue' => $data['days_overdue'],
            ]);

            if ($rsUpdate && $rsAdd) {
                echo json_encode(['status' => 'success', 'message' => 'Xác nhận trả sách thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Xác nhận trả sách thất bại']);
            }
        }
    }
}