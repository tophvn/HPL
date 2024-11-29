<?php
include('../config/database.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Xử lý khi người dùng cập nhật địa chỉ và số điện thoại
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $phonenumber = $_POST['phonenumber'];

    // Kiểm tra số điện thoại hợp lệ
    if (!preg_match('/^[0-9]{1,12}$/', $phonenumber)) {
        echo "<script>alert('Số điện thoại chỉ được chứa số và không được quá 12 số.');</script>";
    } else {
        // Kiểm tra số điện thoại có trùng không
        $checkPhoneQuery = "SELECT * FROM users WHERE phonenumber = '$phonenumber' AND user_id != $user_id";
        $result = $conn->query($checkPhoneQuery);

        if ($result->num_rows > 0) {
            // Số điện thoại đã tồn tại
            echo "<script>alert('Số điện thoại này đã được sử dụng bởi tài khoản khác!');</script>";
        } else {
            // Cập nhật địa chỉ và số điện thoại
            $query = "UPDATE users SET address1 = '$address1', address2 = '$address2', phonenumber = '$phonenumber' WHERE user_id = $user_id";
            $conn->query($query);

            // Cập nhật session để lưu trạng thái tab
            $_SESSION['active_tab'] = 'address';
            header("Location: account.php");
            exit();
        }
    }
}

// Lấy thông tin người dùng
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

require_once '../GoogleAuthenticator/PHPGangsta/GoogleAuthenticator.php';
$ga = new PHPGangsta_GoogleAuthenticator();

// Kích hoạt 2FA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enable_2fa'])) {
    $secret = $ga->createSecret();
    $hashed_secret = $secret; // Mã hóa MD5
    $query = "UPDATE users SET google_auth_secret='$hashed_secret', 2fa_enabled=TRUE WHERE user_id = $user_id"; 
    $conn->query($query);

    // Tạo mã QR để người dùng quét
    $user = $_SESSION['user']['username'];
    $qrCodeUrl = $ga->getQRCodeGoogleUrl($user, $secret, 'HPL-Fashion');
    $_SESSION['qrCodeUrl'] = $qrCodeUrl;
    $_SESSION['secret'] = $secret; 
}

// Tắt 2FA
if (isset($_POST['disable_2fa'])) {
    $query = "UPDATE users SET google_auth_secret=NULL, 2fa_enabled=FALSE WHERE user_id = $user_id";
    $conn->query($query);
    unset($_SESSION['qrCodeUrl']);
    unset($_SESSION['secret']);
}

// Xác thực mã nhập vào từ người dùng
if (isset($_POST['verify_code'])) {
    $code = $_POST['code'];
    $secret = $_SESSION['secret'];

    if ($ga->verifyCode($secret, $code, 2)) { // 2 phút đồng bộ
        echo "Mã xác thực đúng!";
        // Thực hiện hành động sau khi xác thực thành công
    } else {
        echo "Mã xác thực sai!";
    }
}

// Lấy thông tin người dùng
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài Khoản - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h2 class="text-center">TÀI KHOẢN</h2>
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item"><a href="#" id="dashboard-tab">Bảng điều khiển</a></li>
                    <li class="list-group-item"><a href="#" id="address-tab">Cập Nhật</a></li>
                    <li class="list-group-item"><a href="#" id="security-tab">Bảo mật</a></li>
                    <li class="list-group-item"><a href="favorites.php">Danh sách yêu thích</a></li>
                </ul>
            </div>

            <!-- Bảng điều khiển -->
            <div class="col-md-9" id="dashboard-content">
                <div class="account-details">
                    <h4>Thông tin tài khoản</h4>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>Tên:</strong></td>
                                <td><?php echo $user['name'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>E-mail:</strong></td>
                                <td><?php echo $user['email'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Số Điện Thoại:</strong></td>
                                <td><?php echo $user['phonenumber'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ 1:</strong></td>
                                <td><?php echo $user['address1'] ?? ''; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ 2:</strong></td>
                                <td><?php echo $user['address2'] ?? ''; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cập nhật -->
            <div class="col-md-9" id="address-content" style="display:none;">
                <h4>Cập Nhật Địa Chỉ và Số Điện Thoại</h4>
                <form method="POST" action="account.php">
                    <input type="hidden" name="update_address" value="1">
                    <div class="form-group">
                        <label>Địa chỉ 1</label>
                        <input type="text" class="form-control" name="address1" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ 2</label>
                        <input type="text" class="form-control" name="address2">
                    </div>
                    <div class="form-group">
                        <label>Số Điện Thoại</label>
                        <input type="text" class="form-control" name="phonenumber" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật địa chỉ và số điện thoại</button>
                </form>
            </div>

            <!-- Bảo mật -->
            <div class="col-md-9" id="security-content" style="display:none;">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Kích hoạt Google Authenticator</h4>
                        <?php if (!$user['2fa_enabled']): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="enable_2fa" value="1">
                                <button type="submit" class="btn btn-primary">Kích hoạt 2FA</button>
                            </form>
                        <?php else: ?>
                            <p>2FA đã được kích hoạt.</p>
                            <form method="POST" action="">
                                <input type="hidden" name="disable_2fa" value="1">
                                <button type="submit" class="btn btn-danger">Tắt 2FA</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($_SESSION['qrCodeUrl'])): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title text-success">Quét mã QR dưới đây:</h4>
                            <img src="<?php echo $_SESSION['qrCodeUrl']; ?>" alt="QR Code" class="img-fluid mb-3">
                            <p><strong>Mã bí mật:</strong> <?php echo htmlspecialchars($_SESSION['secret']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Xác thực mã -->
                <!-- <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title text-warning">Xác thực mã</h4>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="code">Mã xác thực</label>
                                <input type="text" class="form-control" name="code" id="code" required>
                            </div>
                            <button type="submit" name="verify_code" class="btn btn-success">Xác thực</button>
                        </form>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        // Lấy tab hiện tại từ localStorage
        let currentTab = localStorage.getItem('currentTab') || 'dashboard-tab';

        // Hiển thị nội dung tab hiện tại
        showTabContent(currentTab);

        // Gán sự kiện click cho từng tab
        $('#address-tab').click(function () {
            localStorage.setItem('currentTab', 'address-tab');
            showTabContent('address-tab');
        });

        $('#dashboard-tab').click(function () {
            localStorage.setItem('currentTab', 'dashboard-tab');
            showTabContent('dashboard-tab');
        });

        $('#security-tab').click(function () {
            localStorage.setItem('currentTab', 'security-tab');
            showTabContent('security-tab');
        });

        // Hàm hiển thị nội dung tab
        function showTabContent(tabId) {
            $('#dashboard-content').hide();
            $('#address-content').hide();
            $('#security-content').hide();

            if (tabId === 'address-tab') {
                $('#address-content').show();
            } else if (tabId === 'security-tab') {
                $('#security-content').show();
            } else {
                $('#dashboard-content').show();
            }
        }
    });
</script>

</body>
</html>