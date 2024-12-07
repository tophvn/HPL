<?php
class Database {
    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "shopthoitrang";
    private static $conn = null;

    // Tạo kết nối
    private static function connect() {
        if (self::$conn === null) {
            self::$conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);
            if (self::$conn->connect_error) {
                die("Kết nối thất bại: " . self::$conn->connect_error);
            }
        }
    }

    // Hàm thực thi truy vấn
    public static function query($sql) {
        self::connect();
        return self::$conn->query($sql);
    }

    // Thêm sản phẩm vào danh sách yêu thích
    public static function addToFavorites($user_id, $product_id) {
        $sql = "INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id) ON DUPLICATE KEY UPDATE id = id";
        return self::query($sql);
    }
}
?>
