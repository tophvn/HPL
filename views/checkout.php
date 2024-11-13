<?php
include('../config/Database.php');
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Lấy kết nối từ lớp Database
$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Lấy dữ liệu người dùng và giỏ hàng
$user_query = "SELECT address1, address2 FROM users WHERE user_id = $user_id";
$user = $conn->query($user_query)->fetch_assoc();

$product_query = "SELECT products.product_name, products.price, cart_item.quantity, products.product_id 
                  FROM products 
                  JOIN cart_item ON products.product_id = cart_item.product_id 
                  JOIN cart ON cart_item.cart_id = cart.cart_id 
                  WHERE cart.user_id = $user_id";
$result = $conn->query($product_query);

$products = $result->fetch_all(MYSQLI_ASSOC);
$total = array_reduce($products, fn($carry, $item) => $carry + $item['price'] * $item['quantity'], 0);

// Khởi tạo phí giao hàng
$shipping_fee = 0;
$show_alert = false; // Biến kiểm tra để hiển thị alert

// Xử lý form khi người dùng bấm đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $order_address = $_POST['shipping_address'] ?? '';
    $shipping_method = $_POST['shipping_method'] ?? '';

    // Kiểm tra nếu người dùng không chọn địa chỉ giao hàng
    if (empty($order_address)) {
        $show_alert = true; // Đặt biến để hiển thị cảnh báo
    } else {
        $shipping_fee = ($shipping_method === 'Express') ? 50000 : 0;
        $total += $shipping_fee;

        // Lưu đơn hàng vào bảng `order`
        $order_query = "INSERT INTO `order` (user_id, status_id, order_date, order_address, payment_method, shipping_method, total_amount) 
                        VALUES ($user_id, 1, NOW(), '$order_address', '$payment_method', '$shipping_method', $total)";
        
        if ($conn->query($order_query)) {
            $order_id = $conn->insert_id;

            // Lưu chi tiết đơn hàng vào bảng `order_detail`
            foreach ($products as $product) {
                $detail_query = "INSERT INTO `order_detail` (order_id, product_id, order_quantity) 
                                 VALUES ($order_id, {$product['product_id']}, {$product['quantity']})";
                $conn->query($detail_query);
            }

            // Điều hướng đến trang bill.php
            header("Location: bill.php?order_id=" . $order_id);
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thanh Toán - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href=" " rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script>
        // Hiển thị cảnh báo nếu cần thiết
        window.onload = function() {
            <?php if ($show_alert): ?>
                alert('Vui lòng chọn địa chỉ giao hàng trước khi đặt hàng.');
            <?php endif; ?>
        };
    </script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Thanh Toán</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Thanh Toán</p>
            </div>
        </div>
    </div>

    <!-- Nội dung thanh toán -->
    <div class="container-fluid pt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 d-flex justify-content-between border rounded p-4">
                <!-- Thông tin giao hàng -->
                <div class="w-50 pr-3">
                    <form action="" method="POST">
                        <div class="mb-4">
                            <h4 class="font-weight-semi-bold mb-4">Địa Chỉ Giao Hàng</h4>
                            <div class="form-group">
                                <label>Chọn Địa Chỉ</label>
                                <?php if (empty($user['address1']) && empty($user['address2'])): ?>
                                    <p style="color: red;">Vui lòng thêm địa chỉ trong tài khoản.</p>
                                <?php else: ?>
                                    <select class="form-control" name="shipping_address" required>
                                        <?php if (!empty($user['address1'])) echo "<option value='{$user['address1']}'>{$user['address1']}</option>"; ?>
                                        <?php if (!empty($user['address2'])) echo "<option value='{$user['address2']}'>{$user['address2']}</option>"; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Phương thức giao hàng -->
                        <div class="mb-4">
                            <h4 class="font-weight-semi-bold mb-4">Phương Thức Giao Hàng</h4>
                            <div class="form-group">
                                <label><input type="radio" name="shipping_method" value="Fast" checked> Giao hàng nhanh (0₫)</label><br>
                                <label><input type="radio" name="shipping_method" value="Express"> Giao hàng hỏa tốc (50,000₫)</label>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-4">
                            <h4 class="font-weight-semi-bold mb-4">Phương Thức Thanh Toán</h4>
                            <div class="form-group">
                                <label><input type="radio" name="payment_method" value="Thanh toán khi nhận hàng (COD)" checked> Thanh toán khi nhận hàng (COD)</label><br>
                                <label><input type="radio" name="payment_method" value="Thẻ Tín dụng/Ghi nợ"> Thẻ Tín dụng/Ghi nợ</label><br>
                                <label><input type="radio" name="payment_method" value="Chuyển khoản ngân hàng"> Chuyển khoản ngân hàng</label><br>
                                <label><input type="radio" name="payment_method" value="Thẻ nội địa NAPAS"> Thẻ nội địa NAPAS</label><br>
                            </div>
                        </div>
                        <button class="btn btn-block btn-primary my-3 py-3" type="submit">Đặt Hàng</button>
                    </form>
                </div>

                <!-- Chi tiết đơn hàng -->
                <div class="w-50 pl-3">
                    <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Tổng Đơn Hàng</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-medium mb-3">Sản Phẩm</h5>
                            <?php foreach ($products as $product): ?>
                            <div class="d-flex justify-content-between">
                                <p><?php echo htmlspecialchars($product['product_name']) . ' x ' . htmlspecialchars($product['quantity']); ?></p>
                                <p><?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?>₫</p>
                            </div>
                            <?php endforeach; ?>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6>Tổng Cộng</h6>
                                <h6><?php echo number_format($total, 0, ',', '.'); ?>₫</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6>Phí Giao Hàng</h6>
                                <h6><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</h6>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6>Tổng Thanh Toán</h6>
                                <h6><?php echo number_format($total + $shipping_fee, 0, ',', '.'); ?>₫</h6> <!-- Cập nhật tổng thanh toán -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>