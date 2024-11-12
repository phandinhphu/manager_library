<?php

class FineModel extends Model
{
    public string $table = 'fines';

    public function __construct()
    {
        parent::__construct();
    }

    public function getNewReaderViolated(): array
    {
        $sql = "SELECT $this->table.id, users.user_name, books.book_name, 
                fine_amount, borrowbooks.book_status, borrowbooks.return_date
                FROM $this->table, users, borrowbooks, books
                WHERE $this->table.borrow_id = borrowbooks.id AND borrowbooks.user_id = users.id
                AND borrowbooks.book_id = books.id AND fine_amount > 0
                ORDER BY $this->table.id DESC
                LIMIT 0, 5";
        $rs = $this->db->getAll($sql);

        if ($rs) return $rs;
        return [];
    }
}