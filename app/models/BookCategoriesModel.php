<?php

class BookCategoriesModel extends Model
{
    public string $table = 'book_categories';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @param $data
     * @return false|PDOStatement
     * @since 2024/10/14
     */
    public function insertBookCategories($data): false|PDOStatement
    {
        return $this->db->insert($this->table, $data);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/15
     * @param $id
     * @return false|PDOStatement
     */
    public function deleteBookCategories($id): false|PDOStatement
    {
        return $this->db->delete($this->table, ['book_id' => $id]);
    }
}