<?php
include('../../config/database.php'); 
include('../../config/config.php');

$errors = []; // Mảng lưu trữ lỗi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user'; 

    // Kiểm tra xem username có chứa dấu hoặc khoảng trắng
    if (preg_match('/[áàảãạăắằẳẵặâấầẩẫậéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵđ\s]/i', $username)) {
        $errors['username'] = 'Tên đăng nhập không được chứa dấu hoặc khoảng trắng!';
    }

    // Kiểm tra mật khẩu
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Mật khẩu không trùng khớp!';
    }

    $conn = Database::getConnection();

    // Kiểm tra tồn tại tên đăng nhập hoặc email
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors['username_email'] = 'Tên đăng nhập hoặc email đã tồn tại!';
    }

    // nếu không có lỗi thì thêm người dùng vào database
    if (empty($errors)) {
        $hashedPassword = md5($password);
        $sql = "INSERT INTO users (username, password, phonenumber, name, email, roles) 
                VALUES ('$username', '$hashedPassword', '$phonenumber', '$name', '$email', '$role')";
        if (mysqli_query($conn, $sql)) {
            header("Location: " . BASE_URL . "views/auth/login.php");
            exit(); 
        } else {
            $errors['database'] = 'Đăng ký không thành công!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/css-login-register.css">
</head>
<body>
    <div class="site-wrap d-md-flex align-items-stretch">
        <div class="bg-img" style="background-image: url('<?php echo BASE_URL; ?>img/back-regis.jpg')"></div>
        <div class="form-wrap">
            <div class="form-inner">
                <h1 class="title">Đăng Ký</h1>
                <p class="caption mb-4">Tạo tài khoản của bạn chỉ trong vài giây.</p>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" class="pt-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Tên Đăng Nhập" required>
                        <label for="username">Tên Đăng Nhập</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Họ và Tên" required>
                        <label for="name">Họ và Tên</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" id="email" placeholder="info@example.com" required>
                        <label for="email">Địa Chỉ Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber" placeholder="Số Điện Thoại" required>
                        <label for="phonenumber">Số Điện Thoại</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu" required>
                        <label for="password">Mật Khẩu</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Xác Nhận Mật Khẩu" required>
                        <label for="confirm_password">Xác Nhận Mật Khẩu</label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" required>
                            <label for="remember" class="form-check-label">Tôi đồng ý với <a href="#">Điều Khoản Dịch Vụ</a> và <a href="#">Chính Sách Bảo Mật</a></label>
                        </div>
                    </div>
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
                    </div>
                    <div class="mb-2">Đã có tài khoản? <a href="<?php echo BASE_URL; ?>views/auth/login.php">Đăng Nhập</a></div>
                    <div class="social-account-wrap">
                        <h4 class="mb-4"><span>hoặc tiếp tục với</span></h4>
                        <ul class="list-unstyled social-account d-flex justify-content-between">
                            <li><a href="#"><img src="<?php echo BASE_URL; ?>assets/Icon/icon-google.svg" alt="Logo Google"></a></li>
                            <li><a href="#"><img src="<?php echo BASE_URL; ?>assets/Icon/icon-facebook.svg" alt="Logo Facebook"></a></li>
                            <li><a href="#"><img src="<?php echo BASE_URL; ?>assets/Icon/icon-apple.svg" alt="Logo Apple"></a></li>
                            <li><a href="#"><img src="<?php echo BASE_URL; ?>assets/Icon/icon-twitter.svg" alt="Logo Twitter"></a></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- nút trở về trang chủ -->
    <a href="<?php echo BASE_URL; ?>index.php" class="btn" style="position: fixed; bottom: 20px; right: 20px; display: inline-flex; align-items: center; background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 50px; height: 50px; justify-content: center; z-index: 1000;">
        <i class="uil uil-estate" style="font-size: 1.5rem; color: #007bff;"></i>
    </a>
    <script src="<?php echo BASE_URL; ?>js/custom.js"></script>
</body>
</html>