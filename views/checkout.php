<?php
include('../config/database.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

$user_query = "SELECT address1, address2 FROM users WHERE user_id = $user_id";
$user = $conn->query($user_query)->fetch_assoc();

$product_query = "SELECT products.product_name, products.price AS original_price, cart_item.price AS discount_price, cart_item.quantity, products.product_id 
                  FROM products 
                  JOIN cart_item ON products.product_id = cart_item.product_id 
                  JOIN cart ON cart_item.cart_id = cart.cart_id 
                  WHERE cart.user_id = $user_id";
$result = $conn->query($product_query);

$products = $result->fetch_all(MYSQLI_ASSOC);
$total = array_reduce($products, fn($carry, $item) => $carry + $item['discount_price'] * $item['quantity'], 0);

$shipping_fee = 0;
$show_alert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $order_address = $_POST['shipping_address'] ?? '';
    $shipping_method = $_POST['shipping_method'] ?? '';

    if (empty($order_address)) {
        $show_alert = true; 
    } else {
        // Xác định phí giao hàng
        if ($shipping_method === 'Express') {
            $shipping_fee = 50000; // Phí giao hàng hỏa tốc
        }

        // Tính tổng số tiền bao gồm cả phí giao hàng
        $total_amount = $total + $shipping_fee;

        // Kiểm tra phương thức thanh toán
        if ($payment_method === 'Chuyển khoản ngân hàng') {
            // Chuyển hướng đến trang thanh toán VNPAY
            header("Location: online_checkout_vnpay.php?amount=$total_amount&order_type=don_hang");
            exit();
        }

        // Xử lý cho phương thức thanh toán khác
        // Chèn vào bảng `bills` trước để lấy bill_id
        $bill_query = "INSERT INTO bills (user_id, bill_date, shipping_address, shipping_fee, total_amount, payment_method, shipping_method)
                       VALUES ($user_id, NOW(), '$order_address', $shipping_fee, $total_amount, '$payment_method', '$shipping_method')";
        
        if ($conn->query($bill_query)) {
            $bill_id = $conn->insert_id; // Lấy bill_id vừa tạo

            // Chèn vào bảng `order` trước để lấy order_id
            $order_query = "INSERT INTO `order` (user_id, order_address, payment_method, total_amount, shipping_method, order_date, status_id)
                            VALUES ($user_id, '$order_address', '$payment_method', $total_amount, '$shipping_method', NOW(), 1)";
            
            if ($conn->query($order_query)) {
                $order_id = $conn->insert_id; // Lấy order_id vừa tạo

                // Chèn chi tiết sản phẩm vào bảng `order_detail` và bảng `bill_items`
                foreach ($products as $product) {
                    // Chèn vào bảng `order_detail`
                    $detail_query = "INSERT INTO `order_detail` (order_id, product_id, order_quantity)
                                     VALUES ($order_id, {$product['product_id']}, {$product['quantity']})";
                    $conn->query($detail_query);

                    // Chèn vào bảng `bill_items`
                    $subtotal_price = $product['discount_price'] * $product['quantity']; // Tính tổng giá cho sản phẩm
                    $bill_item_query = "INSERT INTO bill_items (bill_id, product_id, product_name, quantity, original_price, discount_price, subtotal_price)
                                        VALUES ($bill_id, {$product['product_id']}, '{$product['product_name']}', {$product['quantity']}, {$product['original_price']}, {$product['discount_price']}, $subtotal_price)";
                    $conn->query($bill_item_query);
                }

                header("Location: bill.php?order_id=" . $order_id);
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
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

    <div class="container-fluid pt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 d-flex justify-content-between border rounded p-4">
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

                        <div class="mb-4">
                            <h4 class="font-weight-semi-bold mb-4">Phương Thức Giao Hàng</h4>
                            <div class="form-group">
                                <label><input type="radio" name="shipping_method" value="Fast" checked> Giao hàng nhanh (0₫)</label><br>
                                <label><input type="radio" name="shipping_method" value="Express"> Giao hàng hỏa tốc (50,000₫)</label>
                            </div>
                        </div>

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

                <div class="w-50 pl-3">
                    <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Đơn Hàng Của Bạn</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <?php foreach ($products as $product): ?>
                                    <li class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="m-0"><?= $product['product_name'] ?> x <?= $product['quantity'] ?></p>
                                        <p class="m-0"><?= number_format($product['discount_price'] * $product['quantity'], 0) ?>₫</p>
                                    </li>
                                <?php endforeach; ?>
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="m-0">Phí giao hàng</p>
                                    <p class="m-0"><?= number_format($shipping_fee, 0) ?>₫</p>
                                </li>
                                <li class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="font-weight-semi-bold m-0">Tổng cộng</h6>
                                    <h6 class="font-weight-semi-bold m-0"><?= number_format($total + $shipping_fee, 0) ?>₫</h6>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>