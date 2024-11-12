<?php
class UserModel extends Model
{
    public string $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param array $where
     * @return array|false
     */
    public function getUser($where = []): false|array
    {
        $condition = implode(' AND ', array_map(fn($k) => "$k = :$k", array_keys($where)));
        $sql = "SELECT * FROM $this->table WHERE $condition";
        return $this->db->getOne($sql, $where);
    }

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param string $email
     * @param string $password
     * @return false|array
     */
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param $userId
     * @return false|PDOStatement
     */
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

    /***
     * @author Phan Đình Phú
     * @since 2024/11/12
     * @param $userId
     * @return false|PDOStatement
     */
    public function getUserByName($username, $page): false|array
    {
        $offset = ($page - 1) * $this->limit;
        $sql = "SELECT * FROM $this->table WHERE user_name LIKE :username";

        $total = $this->db->getAll($sql, ['username' => "%$username%"]);

        return [
            'data' => $this->db->getAll($sql . " LIMIT $offset, $this->limit", ['username' => "%$username%"]),
            'total' => count($total),
        ];
    }
}