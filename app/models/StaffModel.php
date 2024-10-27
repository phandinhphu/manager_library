<?php
class StaffModel extends Model
{
    public string $table = 'staffs';

    public function __construct()
    {
        parent::__construct();
    }

    public function getUser($where = []): false|array
    {
        $condition = implode(' AND ', array_map(fn($k) => "$k = :$k", array_keys($where)));
        $sql = "SELECT * FROM $this->table WHERE $condition";
        return $this->db->getOne($sql, $where);
    }

    public function login($email, $password): false|array
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $user = $this->db->getOne($sql, ['email' => $email]);
        if ($user) {
            if (password_verify($password, $user['pass_word'])) {
                return $user;
            }
        }
        return false;
    }
}