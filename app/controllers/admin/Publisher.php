<?php
class Publisher extends Controller{
    public mixed $publisherModel;
    public array $data = [];

    public function __construct(){
        $this->publisherModel = $this->model('PublisherModel');
    }

    public function index(int $page=1): void{
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }
        $publisher = $this->publisherModel->getByPage($page);

        $this->data['headercontent']['tab'] = 'books';
        $this->data['subcontent']['publisher'] = $publisher['data'];
        $this->data['subcontent']['total_pages'] = $this->publisherModel->getTotalPage($publisher['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-nha-xuat-ban/trang-';
        $this->data['subcontent']['script'] = 'publisher';

        $this->data['title'] = 'Quản lý nhà xuất bản';
        $this->data['content'] = 'admin/publisher';

        $this->view('layouts/admin_layout', $this->data);
    }

    public function addPublisher(): void{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'publisher_name' => $_POST['name']
            ];
            if ($this->publisherModel->check(['publisher_name' => $data['publisher_name']])) {
                $this->response(0, 'Nhà xuất bản đã tồn tại!');
                return;
            }
            
            $result = $this->publisherModel->insertPublisher($data);

            $success = $result ? 1 : 0;
            $message = $result ? 'Thêm nhà xuất bản thành công!' : 'Thêm nhà xuất bản thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }

    public function updatePublisher(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'publisher_name' => $_POST['name']
            ];
            $where = ['id' => $_POST['id']];
            if ($this->publisherModel->check(['publisher_name' => $data['publisher_name']])) {
                $this->response(0, 'Nhà xuất bản đã tồn tại!');
                return;
            }
            $result = $this->publisherModel->updatePublisher($data, $where);

            $success = $result ? 1 : 0;
            $message = $result ? 'Cập nhật nhà xuất bản thành công!' : 'Cập nhật nhà xuất bản thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }

    public function deletePublisher(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->publisherModel->deletePublisher(['id' => $id]);

            $success = $result ? 1 : 0;
            $message = $result ? 'Xóa nhà xuất bản thành công!' : 'Xóa nhà xuất bản thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }


}

?>