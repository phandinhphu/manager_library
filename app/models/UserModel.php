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

    /**
     * Lấy danh sách thông tin người dùng theo trang
     * @param int $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function getUserByPage(int $page = 1): array
    {
        $offset = ($page - 1) * $this->limit;
        $sql = "SELECT * FROM $this->table";
        $users = $this->db->getAll($sql . " LIMIT $offset, $this->limit");
        return [
            'data' => $users,
            'total' => count($users),
        ];
    }

    /**
     * Thống kê số lượng sách mượn, trả, phạt của độc giả
     * @param mixed $condition
     * @param mixed $page
     * @return array
     * @author Trần Duy Vương
     * @since 2024-11-24
     */
    public function statistic($condition = [], $page = 1): array
    {
        if(!empty($condition)) {
            $users = $this->getUserByName($condition['reader_name'], $page);
        } else {
            $users = $this->getUserByPage($page);
        }

        $newUsers = array_map(function($user) {
            $quantity = $this->db->getOne("SELECT SUM(bb.quantity) AS total_borrowed,
                                                            SUM(IF(f.borrow_id IS NOT NULL, bb.quantity, 0)) AS total_returned,
                                                            SUM(f.fine_amount) AS total_fine
                                                            FROM borrowbooks bb
                                                            LEFT JOIN fines f ON bb.id = f.borrow_id
                                                            WHERE bb.user_id = :user_id", ['user_id' => $user['id']]);
            $user['total_borrowed'] = $quantity['total_borrowed'] ?? 0;
            $user['total_returned'] = $quantity['total_returned'] ?? 0;
            $user['total_fine'] = $quantity['total_fine'] ?? 0;
            return $user;
        }, $users['data']);
        $total = $users['total'];

        return [
            'data' => $newUsers,
            'total' => $total,
        ];
    }

    public function getReaderStats(): array
    {
        $stats = $this->db->getOne("SELECT 
            (SELECT COUNT(*) FROM users) as total,
            COUNT(DISTINCT CASE WHEN bb.return_date IS NULL THEN u.id END) as borrowing,
            COUNT(DISTINCT CASE WHEN bb.return_date IS NOT NULL THEN u.id END) as returned,
            COUNT(DISTINCT CASE WHEN bb.return_date < CURRENT_DATE AND bb.return_date IS NULL THEN u.id END) as overdue
        FROM users u
        LEFT JOIN borrowbooks bb ON u.id = bb.user_id");
        return $stats;
    }

    public function getFineStats(): array
    {
        $sql = 
        "SELECT 
            DATE_FORMAT(bb.return_date, :dayFormat) as time_fine,
            SUM(f.fine_amount) as total_amount
        FROM borrowbooks bb
        LEFT JOIN fines f ON bb.id = f.borrow_id
        WHERE bb.return_date IS NOT NULL
            AND f.fine_amount IS NOT NULL
        GROUP BY DATE_FORMAT(bb.return_date, :dayFormat)
        ORDER BY time_fine ASC
        LIMIT 12";

        $stats = $this->db->getAll($sql, ['dayFormat' => '%m/%Y']);
        return array_reverse($stats);
    }
}