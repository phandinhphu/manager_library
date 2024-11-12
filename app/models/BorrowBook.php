<?php

class BorrowBook extends Model
{
    public string $table = 'borrowbooks';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @return array
     */
    public function getNewReaderBorrowing(): array
    {
        $sql = "SELECT * FROM borrowbooks, users, books
                WHERE borrowbooks.user_id = users.id AND borrowbooks.book_id = books.id
                AND return_date IS NULL AND book_status IS NULL
                ORDER BY borrowbooks.id DESC LIMIT 0, 5";
        
        $rs = $this->db->getAll($sql);

        if ($rs) return $rs;
        return [];
    }
}