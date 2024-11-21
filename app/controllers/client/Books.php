<?php
class Books extends Controller
{
    private mixed $bookModel;
    private mixed $authorModel;
    private mixed $commentModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
        $this->authorModel = $this->model('AuthorModel');
        $this->commentModel = $this->model('CommentModel');
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $bookId
     * @return void
     */
    public function detail($bookId): void
    {

        $this->data['subcontent']['book'] = $this->bookModel->getBookById($bookId);
        $this->data['subcontent']['same_category_books'] = $this->bookModel->getSameCategoryBooks($bookId);
        $this->data['subcontent']['same_author_books'] = $this->bookModel->getSameAuthorBooks($bookId);
        $this->data['subcontent']['action'] = $this->bookModel->getAction($bookId);
        $this->data['subcontent']['amount_likes'] = $this->bookModel->getAmountLiked($bookId);
        $this->data['subcontent']['amount_dislikes'] = $this->bookModel->getAmountDisliked($bookId);
        $this->data['subcontent']['comments'] = $this->commentModel->getAllComments($bookId);
        $this->data['content'] = 'client/book-detail';
        $this->data['title'] = 'Chi tiết sách';

        $this->view('layouts/client_layout', $this->data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $bookId
     * @return void
     */
    public function like($bookId): void
    {
        if (!isset($_SESSION['user']['user_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này'
            ]);
        } else {
            $this->bookModel->like($bookId);
            $amountLikes = $this->bookModel->getAmountLiked($bookId);
            $amountDislikes = $this->bookModel->getAmountDisliked($bookId);

            echo json_encode([
                'status' => 'success',
                'amount_likes' => $amountLikes,
                'amount_dislikes' => $amountDislikes
            ]);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/20
     * @param $bookId
     * @return void
     */
    public function dislike($bookId): void
    {
        if (!isset($_SESSION['user']['user_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này'
            ]);
        } else {
            $this->bookModel->dislike($bookId);
            $amountLikes = $this->bookModel->getAmountLiked($bookId);
            $amountDislikes = $this->bookModel->getAmountDisliked($bookId);

            echo json_encode([
                'status' => 'success',
                'amount_likes' => $amountLikes,
                'amount_dislikes' => $amountDislikes
            ]);
        }
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/31
     * @param $page
     * @return void
     */
    public function wishList($page = 1): void
    {
        if (isset($_GET['book_id']) && isset($_GET['action'])) {
            $bookId = $_GET['book_id'];
            if (!isset($_SESSION['user']['user_id'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Bạn cần đăng nhập để thực hiện chức năng này'
                ]);
            } else {
                if ($_GET['action'] == 'remove') {
                    $rs = $this->bookModel->removeFromWishList($bookId);
                    if ($rs) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Xóa sách khỏi danh sách mong muốn thành công'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Xóa sách khỏi danh sách mong muốn thất bại'
                        ]);
                    }
                } else {
                    $rs = $this->bookModel->addWishList($bookId);
                    if ($rs) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Thêm sách vào danh sách mong muốn thành công'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Bạn đã thêm sách này vào danh sách mong muốn hoặc số lượng sách đã hết'
                        ]);
                    }
                }
            }
        } else {
            $conditions = $_GET;

            array_shift($conditions);

            $booksWishList = $this->bookModel->getBooksWishListByPage($page, $conditions);

            $this->data['headercontent']['tab'] = 'wishlist';
            $this->data['subcontent']['books_wishlist'] = $booksWishList['data'];
            $this->data['subcontent']['authors'] = $this->authorModel->getAllAuthors();
            $this->data['subcontent']['total_pages'] = $this->bookModel->getTotalPage($booksWishList['total']);
            $this->data['subcontent']['page'] = $page;
            $this->data['subcontent']['param_string'] = $this->bookModel->createParamString($conditions);
            $this->data['subcontent']['url_page'] = WEB_ROOT . '/danh-sach-mong-muon/trang-';

            $this->data['title'] = 'Danh sách mong muốn';
            $this->data['content'] = 'client/wishlist';

            $this->view('layouts/client_layout', $this->data);
        }
    }
}