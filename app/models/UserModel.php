<?php
class UserModel extends Model
{
    public string $table = 'users';

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

    public function updateActive($userId): false|PDOStatement
    {
        $dataUpdate = [
            'status_account' => 1,
            'active_token' => '',
            'update_at' => date('Y-m-d H:i:s')
        ];

        $where = [
            'id' => $userId,
        ];

        return $this->db->update($this->table, $dataUpdate, $where);
    }
}