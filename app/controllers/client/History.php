<?php

class History extends Controller
{
    private mixed $authorModel;
    private mixed $borrowBookModel;
    private array $data = [];

    public function __construct()
    {
        $this->authorModel = $this->model('AuthorModel');
        $this->borrowBookModel = $this->model('BorrowBookModel');
    }

    /**
     * @author Phan Đình Phú
     * @since 2024/11/25
     * @param int $page
     * @return void
     */
    public function index($page = 1): void
    {
        $returnBooks = $this->borrowBookModel->getBooksReturned($_SESSION['user']['user_id'], [], '', $page);

        $this->data['headercontent']['tab'] = 'history';
        $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
        $this->data['subcontent']['returnBooks'] = $returnBooks['data'];
        $this->data['subcontent']['statistic'] = [
            'total' => $this->borrowBookModel->getTotal($_SESSION['user']['user_id']),
            'total_return' => $this->borrowBookModel->sumBooksReturned($_SESSION['user']['user_id']),
            'total_return_overdue' => $this->borrowBookModel->sumBooksReturned($_SESSION['user']['user_id'], 'overdue'),
            'total_borrow' => $this->borrowBookModel->sumBooksBorrow($_SESSION['user']['user_id']),
            'total_borrow_overdue' => $this->borrowBookModel->sumBooksBorrow($_SESSION['user']['user_id'], 'overdue'),
            'total_fine' => $this->borrowBookModel->sumFineAmount($_SESSION['user']['user_id'])
        ];
        $this->data['subcontent']['total_pages'] = $this->borrowBookModel->getTotalPage($returnBooks['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/lich-su/trang-';

        $this->data['title'] = 'History';
        $this->data['content'] = 'client/history';

        $this->view('layouts/client_layout', $this->data);
    }

    public function search($page = 1): void
    {
        $condition = [];
        $filter = '';

        if (isset($_GET['book_name']) && !empty($_GET['book_name'])) {
            $condition['book_name'] = $_GET['book_name'];
        }

        if (isset($_GET['author']) && !empty($_GET['author'])) {
            $condition['author_id'] = $_GET['author'];
        }

        if (isset($_GET['code_isbn']) && !empty($_GET['code_isbn'])) {
            $condition['isbn_code'] = $_GET['code_isbn'];
        }

        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $filter = $_GET['sort'];
        }

        $returnBooks = $this->borrowBookModel->getBooksReturned($_SESSION['user']['user_id'], $condition, $filter, $page);

        $this->data['headercontent']['tab'] = 'history';
        $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
        $this->data['subcontent']['returnBooks'] = $returnBooks['data'];
        $this->data['subcontent']['statistic'] = [
            'total' => $this->borrowBookModel->getTotal($_SESSION['user']['user_id']),
            'total_return' => $this->borrowBookModel->sumBooksReturned($_SESSION['user']['user_id']),
            'total_return_overdue' => $this->borrowBookModel->sumBooksReturned($_SESSION['user']['user_id'], 'overdue'),
            'total_borrow' => $this->borrowBookModel->sumBooksBorrow($_SESSION['user']['user_id']),
            'total_borrow_overdue' => $this->borrowBookModel->sumBooksBorrow($_SESSION['user']['user_id'], 'overdue'),
            'total_fine' => $this->borrowBookModel->sumFineAmount($_SESSION['user']['user_id'])
        ];
        $this->data['subcontent']['total_pages'] = $this->borrowBookModel->getTotalPage($returnBooks['total']);
        $this->data['subcontent']['page'] = $page;
        $this->data['subcontent']['url_page'] = WEB_ROOT . '/lich-su/tim-kiem/trang-';
        $this->data['subcontent']['param_string'] = $this->createParamString($condition, $filter);

        $this->data['title'] = 'History';
        $this->data['content'] = 'client/history';

        $this->view('layouts/client_layout', $this->data);
    }

    private function createParamString($where = [], $filter = ''): string
    {
        $param = '';

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $param .= $key . '=' . $value . '&';
            }
        }

        if (!empty($filter)) {
            $param .= 'sort=' . $filter;
        }

        return $param;
    }
}