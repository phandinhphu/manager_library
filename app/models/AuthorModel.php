<?php

class AuthorModel extends Model
{
    public string $table = 'authors';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/10/14
     * @return array
     */
    public function getAllAuthors(): array
    {
        return $this->getAll('*', 'all');
    }
}