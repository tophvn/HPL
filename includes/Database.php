<?php
class Database
{
    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "shopthoitrang";
    private static $conn;

    // Hàm tạo kết nối
    public static function getConnection()
    {
        if (self::$conn == null) {
            self::$conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);
            if (self::$conn->connect_error) {
                die("Kết nối thất bại: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    // Hàm thực thi truy vấn
    public static function query($sql, $types = null, ...$params)
    {
        $conn = self::getConnection();
        if ($stmt = $conn->prepare($sql)) {
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            return false;
        }
    }
    
}
