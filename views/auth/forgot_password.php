<?php
include('../../config/database.php'); 
include('../../config/config.php'); 

// Yêu cầu thư viện PHPMailer
require "../../PHPMailer/src/PHPMailer.php";
require "../../PHPMailer/src/SMTP.php";
require "../../PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$message = '';

// Kiểm tra nếu biểu mẫu đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $conn = Database::getConnection();

    // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
    $query = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Tạo mã đặt lại mật khẩu (token)
        $token = bin2hex(random_bytes(50));

        // Lưu token vào cơ sở dữ liệu
        $query = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";
        $conn->query($query);

        // Gửi email
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0; // Hiển thị chi tiết lỗi khi gửi email
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoanghai07077@gmail.com';
            $mail->Password = 'rnqg fgie hdoz xwkk'; // Thay bằng mật khẩu ứng dụng
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('hoanghai07077@gmail.com', 'HPL - Fashion');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Đặt Lại Mật Khẩu';
            $resetLink = BASE_URL . "views/auth/reset_password.php?token=" . $token;
            $mail->Body = "Vui lòng nhấp vào liên kết này để đặt lại mật khẩu của bạn: <a href='$resetLink'>$resetLink</a><br><br> Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.";
            $mail->send();
            $message = 'Đã gửi hướng dẫn đặt lại mật khẩu đến email của bạn.';
        } catch (Exception $e) {
            $errors[] = 'Lỗi khi gửi email: ' . $mail->ErrorInfo;
        }

    } else {
        $errors[] = 'Email không tồn tại trong hệ thống.';
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/css-login-register.css">
</head>
<body>
    <div class="site-wrap d-md-flex align-items-stretch">
        <div class="bg-img" style="background-image: url('<?php echo BASE_URL; ?>img/back-reset.jpg')"></div>
        <div class="form-wrap">
            <div class="form-inner">
                <h1 class="title">Quên Mật Khẩu</h1>
                <p class="caption mb-4">Vui lòng nhập địa chỉ email của bạn để đặt lại mật khẩu.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($message): ?>
                    <div class="alert alert-success">
                        <p><?php echo $message; ?></p>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="pt-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                        <label for="email">Địa chỉ Email</label>
                    </div>
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary">Đặt Lại Mật Khẩu</button>
                    </div>
                    <div class="mb-2">Quay lại <a href="login.php">Đăng Nhập</a></div>
                </form>
            </div>
        </div>
    </div>
    <a href="<?php echo BASE_URL; ?>index.php" class="btn" style="position: fixed; bottom: 20px; right: 20px; display: inline-flex; align-items: center; background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 50px; height: 50px; justify-content: center; z-index: 1000;">
        <i class="uil uil-estate" style="font-size: 1.5rem; color: #007bff;"></i>
    </a>
    <script src="<?php echo BASE_URL; ?>js/custom.js"></script>
</body>
</html>