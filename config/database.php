<?php
class Database {
    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "shopthoitrang";
    private static $conn = null;
    // tạo kết nối
    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);
            if (self::$conn->connect_error) {
                die("Kết nối thất bại: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
    // hàm thực thi truy vấn
    public static function query($sql) {
        $conn = self::getConnection();
        return $conn->query($sql);
    }
}
?>
