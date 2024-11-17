<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require '../vendor/autoload.php';

require '../config/database.php';
require '../core/Connection.php';
require '../core/Database.php';
require '../core/Model.php';
require '../app/models/CommentModel.php';

class WebSocketServer implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $bookRooms;
    protected array $userRooms;
    public mixed $commentModel = null;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->commentModel = CommentModel::getInstance();
        $this->bookRooms = [];
        $this->userRooms = [];
    }

    function onOpen(ConnectionInterface $conn): void
    {
        // TODO: Implement onOpen() method.
        // Lưu trữ kết nối mới
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn): void
    {
        // TODO: Implement onClose() method.
        // Xóa kết nối khi người dùng tắt trình duyệt
        $conn->close();
    }

    function onError(ConnectionInterface $conn, \Exception $e): void
    {
        // TODO: Implement onError() method.
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        // TODO: Implement onMessage() method.
        $data = json_decode($msg);
        $bookId = $data->book_id ?? null;
        $userId = $data->user_id ?? null;

        // Nếu tin nhắn là một yêu cầu theo dõi
        if (isset($data->action) && $data->action === 'subscribe') {
            $this->bookRooms[$bookId][] = $from;
        }

        // Nếu tin nhắn là một yêu cầu tham gia
        if (isset($data->action) && $data->action === 'join') {
            $this->userRooms[$userId][] = $from;
        }

        // Nếu tin nhắn là một binh luận mới
        if (isset($data->action) && $data->action === 'newcomment') {
            // Lưu bình luận vào CSDL
            $this->commentModel->Add([
                'book_id' => $bookId,
                'user_id' => $data->user_id,
                'content' => $data->comment,
                'created_at' => $data->created_at
            ]);

            // Gửi bình luân đến tất cả người dùng đang theo dõi cuốn sách
            foreach ($this->bookRooms[$bookId] as $client) {
                $client->send(json_encode([
                    'action' => 'newcomment',
                    'book_id' => $bookId,
                    'comment' => $data->comment,
                    'username' => $data->username,
                    'avatar' => $data->avatar,
                    'created_at' => $data->created_at
                ]));
            }
        }

        // Nếu tin nhắn là một thông báo
        if (isset($data->action) && $data->action === 'notify') {
            // Gửi thông báo đến người dùng dựa theo id
            foreach ($this->userRooms[$userId] as $client) {
                $client->send(json_encode([
                    'action' => 'notify',
                    'type' => $data->type,
                    'message' => $data->message
                ]));
            }
        }
    }
}

// Tạo server WebSocket
$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new WebSocketServer()
        )
    ),
    8081
);

$server->run();