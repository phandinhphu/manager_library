<?php

class CommentModel extends Model
{
    public string $table = 'comments';
    public static ?CommentModel $instance = null;

    public static function getInstance(): CommentModel
    {
        return self::$instance ?? self::$instance = new CommentModel();
    }

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/19
     * @param $bookId
     * @param $userId
     * @return array
     */
    public function getAllComments($bookId): array
    {
        $sql = "SELECT $this->table.*, users.user_name as user_name, users.avatar as avatar
                FROM $this->table, users
                WHERE $this->table.user_id = users.id and book_id = :book_id
                ORDER BY created_at DESC";
        $rs = $this->db->getAll($sql, ['book_id' => $bookId]);

        if ($rs) return $rs;
        return [];
    }
}