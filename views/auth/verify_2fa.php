<?php
session_start();
include('../../config/database.php');
include('../../config/config.php');
require_once '../../GoogleAuthenticator/PHPGangsta/GoogleAuthenticator.php';
$ga = new PHPGangsta_GoogleAuthenticator();
// Lấy thông tin người dùng tạm thời
if (!isset($_SESSION['temp_user'])) {
    header("Location: login.php"); 
    exit();
}
$user = $_SESSION['temp_user'];
$errors = [];
// Lấy secret gốc từ cơ sở dữ liệu
$secret = $user['google_auth_secret'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    if ($ga->verifyCode($secret, $code, 2)) {
        // Mã đúng, lưu thông tin vào session
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'roles' => $user['roles'],
            'google_auth_secret' => $user['google_auth_secret'],
            '2fa_enabled' => $user['2fa_enabled']
        ];
        // Xóa thông tin tạm
        unset($_SESSION['temp_user']);
        header("Location: " . BASE_URL . "index.php"); 
        exit();
    } else {
        $errors[] = "Mã xác thực không chính xác!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../../img/logo/HPL-logo.png" rel="icon">
    <title>Xác Thực</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="../../css/css-login-register.css">
</head>
<body>
    <div class="site-wrap d-md-flex align-items-stretch">
        <div class="bg-img" style="background-image: url('../../img/auth-background/back-2fa.jpg')"></div>
        <div class="form-wrap">
            <div class="form-inner">
                <h1 class="title">Xác thực mã</h1>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="" class="pt-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Mã xác thực" required>
                        <label for="code">Mã xác thực</label>
                    </div>
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary">Xác thực</button>
                    </div>
                    <div class="mb-2">Quay lại <a href="login.php">Đăng Nhập</a></div>
                </form>
            </div>
        </div>
    </div>
    <a href="../../index.php" class="btn" style="position: fixed; bottom: 20px; right: 20px; display: inline-flex; align-items: center; background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 50px; height: 50px; justify-content: center; z-index: 1000;">
        <i class="uil uil-estate" style="font-size: 1.5rem; color: #6610f2;"></i>
    </a>
    <script src="../../js/custom.js"></script>
    <!-- <script>
        const sessionData = <?php echo json_encode($_SESSION); ?>;
        // Lưu vào Session Storage
        sessionStorage.setItem('sessionData', JSON.stringify(sessionData));
        console.log('Session data đã được lưu:', sessionData);
    </script> -->
</body>
</html>