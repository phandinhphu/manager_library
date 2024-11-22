<?php
class Author extends Controller
{
  private mixed $authorModel;
  private array $data = [];


  public function __construct() {
    // gọi đến csdl
    $this->authorModel = $this->model('AuthorModel');
  }

  public function index(int $page = 1): void
  {
    // lấy data tác giả từ model thông qua constructor
    $author = $this->authorModel->getByPage($page);

    $this->data['headercontent']['tab'] = 'author';
    $this->data['subcontent']['author'] = $author['data'];
    $this->data['subcontent']['total_pages'] = $this->authorModel->getTotalPage($author['total'] ?? 0);
    $this->data['subcontent']['page'] = $page;
    $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-tac-gia/trang-';
    $this->data['subcontent']['script'] = 'author'; // gọi đến file author.js

    $this->data['title'] = 'Quản lý tác giả';
    $this->data['content'] = 'admin/author';

    $this->view('layouts/admin_layout', $this->data);
  }

  public function get(): void {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $row = $this->authorModel->getOne(['id' => $id]);
        if($row) {
            $author = [
                'name' => $row['author_name'],
            ];

            header('Content-type: application/json');
            echo json_encode($author);
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

  // hàm thêm tác giả
  public function insert(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'author_name' => $_POST['name']
        ];

        $result = $this->authorModel->insert($data);

        $success = $result ? 1 : 0;
        $message = $result ? 'Thêm tác giả thành công!' : 'Thêm tác giả thất bại!';

        $this->response($success, $message);
        return;
    } else {
        $this->response(0, 'Phương thức không hợp lệ');
        return;
    }
  }

  // cập nhật tác giả
  public function update(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
          'author_name' => $_POST['name']
      ];
      $where = ['id' => $_POST['id']];
      $result = $this->authorModel->update($data, $where);

      $success = $result ? 1 : 0;
      $message = $result ? 'Cập nhật tác giả thành công!' : 'Cập nhật tác giả thất bại!';

      $this->response($success, $message);
      return;
    } else {
      $this->response(0, 'Phương thức không hợp lệ');
      return;
    }
  }

  // xóa tác giả
  public function delete(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $result = $this->authorModel->delete(['id' => $id]);

      $success = $result ? 1 : 0;
      $message = $result ? 'Xóa tác giả thành công!' : 'Xóa tác giả thất bại!';

      $this->response($success, $message);
      return;
    } else {
      $this->response(0, 'Phương thức không hợp lệ');
      return;
    }
  }

  // tìm kiếm tác giả

  public function search(int $page = 1): void
  {
    if (!isset($_SESSION['admin'])) {
      header('Location: ' . WEB_ROOT . '/quan-tri/dang-nhap');
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $keyword = $_GET['search-keyword'] ?? '';
      
      $author = $this->authorModel->search($keyword);

      $this->data['headercontent']['tab'] = 'authors';
      $this->data['subcontent']['author'] = $author['data'];
      $this->data['subcontent']['total_pages'] = $this->authorModel->getTotalPage($author['total'] ?? 0);
      $this->data['subcontent']['page'] = $page;
      $this->data['subcontent']['url_page'] = WEB_ROOT . '/quan-tri/quan-ly-tac-gia/trang-';
      $this->data['subcontent']['script'] = 'author';

      $this->data['title'] = 'Quản lý tác giả';
      $this->data['content'] = 'admin/author';

      $this->view('layouts/admin_layout', $this->data);
    } else {
      $this->response(0, 'Phương thức không hợp lệ');
      return;
    }
  }
}
