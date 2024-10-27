<?php

class PublisherModel extends Model
{
    public string $table = 'publishers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPublishers(): array
    {
        return $this->getAll('*', 'all');
    }
}