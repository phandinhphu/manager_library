<?php
class Connection {
    private static $conn = null;
    private static ?Connection $instance = null;

    private function __construct() {  }

    public static function GetInstance(): Connection
    {
        return self::$instance ?? self::$instance = new Connection();
    }

    public function GetConnection() {
        if (self::$conn != null) {
            return self::$conn;
        }

        try {
            global $configs;

            $dsn = 'mysql:host=' . $configs['database']['host'] . ';dbname=' . $configs['database']['database'];

            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];

            self::$conn = new PDO($dsn, $configs['database']['username'], $configs['database']['password'], $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return self::$conn;
    }
}