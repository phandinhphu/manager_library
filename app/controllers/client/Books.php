<?php
class Books extends Controller
{
    private mixed $bookModel;
    private mixed $commentModel;
    private array $data = [];

    public function __construct()
    {
        $this->bookModel = $this->model('BookModel');
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

            echo json_encode([
                'status' => 'success',
                'amount_likes' => $amountLikes
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
            $amountDislikes = $this->bookModel->getAmountDisliked($bookId);

            echo json_encode([
                'status' => 'success',
                'amount_dislikes' => $amountDislikes
            ]);
        }
    }
}