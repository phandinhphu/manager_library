<?php

class Category extends Controller 
{
    private mixed $categoryModel;
    private array $data = [];

    public function __construct()
    {
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Hiển thị danh sách thể loại
     * @param int $page
     * @return void
     * @author Trần Duy Vương
     * @since 2024-10-31
     */
    public function index(int $page = 1): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        $categories = $this->categoryModel->getByPage($page);

        $this->data['headercontent']['tab'] = 'books';
        $this->data['subcontent']['categories'] = $categories['data'];
        $this->data['subcontent']['total_pages'] = $this->categoryModel->getTotalPage($categories['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-the-loai/trang-';

        $this->data['title'] = 'Quản lý thể loại';
        $this->data['content'] = 'admin/category';

        $this->view('layouts/admin_layout', $this->data);
    }

    /**
     * Lấy dữ liệu thể loại theo id
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    public function get(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
                $row = $this->categoryModel->getOne(['id' => $id]);

                if($row) {
                    $category = [
                        'name' => $row['category_name'],
                    ];

                    header('Content-type: application/json');
                    echo json_encode($category);
                    return;
                }

            } else {
                $this->response(0, 'Không lấy được dữ liệu vui lòng thử lại!');
                return;
            }
        } else {
            $this->response(0, 'Phương thức không hợp lệ!');
            return;
        }
    }

    /**
     * Thêm thể loại
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    public function insert(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_name' => $_POST['name']
            ];
            if ($this->categoryModel->check(['category_name' => $data['category_name']])) {
                $this->response(0, 'Thể loại đã tồn tại!');
                return;
            }
            
            $result = $this->categoryModel->Add($data);

            $success = $result ? 1 : 0;
            $message = $result ? 'Thêm thể loại thành công!' : 'Thêm thể loại thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }

    /**
     * Cập nhật thể loại
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_name' => $_POST['name']
            ];
            $where = ['id' => $_POST['id']];
            if ($this->categoryModel->check(['category_name' => $data['category_name']])) {
                $this->response(0, 'Thể loại đã tồn tại!');
                return;
            }
            $result = $this->categoryModel->Update($data, $where);

            $success = $result ? 1 : 0;
            $message = $result ? 'Cập nhật thể loại thành công!' : 'Cập nhật thể loại thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }

    /**
     * Xóa thể loại
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->categoryModel->Delete(['id' => $id]);

            $success = $result ? 1 : 0;
            $message = $result ? 'Xóa thể loại thành công!' : 'Xóa thể loại thất bại!';

            $this->response($success, $message);
            return;
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }

    /**
     * Tìm kiếm thể loại
     * @param int $page
     * @return void
     * @author Trần Duy Vương
     * @since 2024-11-01
     */
    public function search(int $page = 1): void
    {
        if (!isset($_SESSION['admin'])) {
            header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $keyword = $_GET['search-keyword'] ?? '';
            
            $categories = $this->categoryModel->search($keyword);

            $this->data['headercontent']['tab'] = 'books';
            $this->data['subcontent']['categories'] = $categories['data'];
            $this->data['subcontent']['total_pages'] = $this->categoryModel->getTotalPage($categories['total'] ?? 0);
            $this->data['subcontent']['page'] = $page;
            $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-the-loai/trang-';

            $this->data['title'] = 'Quản lý thể loại';
            $this->data['content'] = 'admin/category';

            $this->view('layouts/admin_layout', $this->data);
        } else {
            $this->response(0, 'Phương thức không hợp lệ');
            return;
        }
    }
}