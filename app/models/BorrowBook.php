<?php

class BorrowBook extends Model
{
    public string $table = 'borrowbooks';

    public function __construct()
    {
        parent::__construct();
    }
}