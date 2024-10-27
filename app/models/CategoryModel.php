<?php
class CategoryModel extends Model
{
    public string $table = 'category';

    public function __construct()
    {
        parent::__construct();
    }

    public function insertCategory($data): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }
}