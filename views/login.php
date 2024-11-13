<?php
session_start();
include('../config/Database.php');
require_once '../google-api/vendor/autoload.php'; // Tải Google API Client

// Thông tin cấu hình OAuth 2.0
$clientID = '614640831923-vulhph6ovaq4rbhfb1l4nd2iu5q611go.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/ShopThoiTrang/views/login.php'; 

// Cấu hình Google Client
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$errors = []; // Mảng lưu trữ thông báo lỗi

// Xử lý đăng nhập bằng Google
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (empty($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $google_account_info = (new Google_Service_Oauth2($client))->userinfo->get();
        
        // Lưu thông tin người dùng vào phiên
        $_SESSION['user'] = [
            'user_id' => $google_account_info->id,
            'username' => $google_account_info->email,
            'name' => $google_account_info->name
        ];
        header("Location: ../index.php");
        exit();
    } else {
        $errors[] = 'Đăng nhập bằng Google không thành công!';
    }
}

// Xử lý đăng nhập thông thường
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = md5($_POST['password'] ?? ''); // Mã hóa mật khẩu
    $conn = Database::getConnection();

    // Truy vấn để tìm người dùng
    $query = "SELECT * FROM users WHERE (email = '$login' OR username = '$login') AND password = '$password'";
    $result = $conn->query($query); // Thực thi truy vấn

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'name' => $user['name']
        ];
        header("Location: ../index.php");
        exit();
    } else {
        $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    }
}

// Tạo URL đăng nhập Google
$googleLoginUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="../css/css-login-register.css">
</head>
<body>
    <div class="site-wrap d-md-flex align-items-stretch">
        <div class="bg-img" style="background-image: url('../img/back-login.jpg')"></div>
        <div class="form-wrap">
            <div class="form-inner">
                <h1 class="title">Đăng Nhập</h1>
                <p class="caption mb-4">Vui lòng nhập thông tin đăng nhập của bạn để tiếp tục.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="pt-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Tên đăng nhập hoặc Email" required>
                        <label for="login">Tên đăng nhập hoặc Email</label>
                    </div>

                    <div class="form-floating">
                        <span class="password-show-toggle js-password-show-toggle"><span class="uil"></span></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu" required>
                        <label for="password">Mật Khẩu</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label for="remember" class="form-check-label">Giữ tôi đăng nhập</label>
                        </div>
                        <div><a href="#">Quên mật khẩu?</a></div>
                    </div>
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                    <div class="mb-2">Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a></div>
                    <div class="social-account-wrap">
                        <h4 class="mb-4"><span>hoặc tiếp tục với</span></h4>
                        <ul class="list-unstyled social-account d-flex justify-content-between">
                            <li><a href="<?php echo $googleLoginUrl; ?>"><img src="../assets/Icon/icon-google.svg" alt="Logo Google"></a></li>
                            <li><a href="#"><img src="../assets/Icon/icon-facebook.svg" alt="Logo Facebook"></a></li>
                            <li><a href="#"><img src="../assets/Icon/icon-apple.svg" alt="Logo Apple"></a></li>
                            <li><a href="#"><img src="../assets/Icon/icon-twitter.svg" alt="Logo Twitter"></a></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <a href="../index.php" class="btn" style="position: fixed; bottom: 20px; right: 20px; display: inline-flex; align-items: center; background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 50px; height: 50px; justify-content: center; z-index: 1000;">
        <i class="uil uil-estate" style="font-size: 1.5rem; color: #007bff;"></i>
    </a>
    <script src="../js/custom.js"></script>
</body>
</html>