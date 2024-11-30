<?php
include('../config/database.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

// Lấy kết nối từ lớp Database
$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Lấy dữ liệu người dùng từ cơ sở dữ liệu
$user_query = "SELECT address1, address2 FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Lấy thông tin hóa đơn gần nhất
$order_query = "SELECT bills.bill_id, bills.total_amount, bills.payment_method, bills.shipping_address, bills.shipping_fee, 
bills.shipping_method, bills.bill_date FROM bills WHERE bills.user_id = $user_id ORDER BY bills.bill_date DESC LIMIT 1";

$order_result = $conn->query($order_query);
$order = $order_result->fetch_assoc();

// Kiểm tra thông tin hóa đơn
if ($order) {
    $total_amount = $order['total_amount'] ?? 0;
    $payment_method = $order['payment_method'] ?? 'Chưa xác định';
    $shipping_address = $order['shipping_address'] ?? 'Chưa xác định';
    $shipping_fee = $order['shipping_fee'] ?? 0;
    $shipping_method = $order['shipping_method'] ?? 'Chưa xác định';
    $bill_id = $order['bill_id'] ?? 0;

    // Truy vấn để lấy các sản phẩm trong hóa đơn
    $items_query = "SELECT bill_items.product_name, bill_items.quantity, bill_items.original_price, bill_items.discount_price, 
    bill_items.subtotal_price FROM bill_items WHERE bill_items.bill_id = $bill_id";

    $items_result = $conn->query($items_query);
    $products = $items_result->fetch_all(MYSQLI_ASSOC);

    // Định dạng ngày hóa đơn
    $order_date = date('F d, Y H:i:s', strtotime($order['bill_date']));  // Ngày và giờ hiện tại
} else {
    // Nếu không có hóa đơn, khởi tạo biến với giá trị mặc định
    $total_amount = 0;
    $payment_method = 'Chưa xác định';
    $shipping_address = 'Chưa xác định';
    $shipping_fee = 0;
    $shipping_method = 'Chưa xác định';
    $bill_id = 0;
    $order_date = 'Chưa có dữ liệu';
    $products = [];
}

// Tính tổng số tiền cho các sản phẩm (không bao gồm phí giao hàng)
$temp_total_amount = array_reduce($products, function($carry, $product) {
    return $carry + $product['subtotal_price'];
}, 0);

// Tính tổng số tiền cuối (bao gồm phí giao hàng)
$final_amount = $temp_total_amount + $shipping_fee;

// Cập nhật hoặc lưu lại giá trị tổng cộng vào cơ sở dữ liệu
if ($bill_id > 0) {
    $update_query = "UPDATE bills SET total_amount = $final_amount WHERE bill_id = $bill_id";
    $conn->query($update_query);
} else {
    // Nếu không có hóa đơn cũ, bạn có thể tạo một hóa đơn mới
    $insert_query = "INSERT INTO bills (user_id, total_amount, shipping_fee, shipping_address, payment_method, shipping_method, bill_date) 
    VALUES ($user_id, $final_amount, $shipping_fee, '$shipping_address', '$payment_method', '$shipping_method', NOW())";
    $conn->query($insert_query);
}

// Định dạng tiền
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/HPL-logo.png" rel="icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.3/css/foundation.min.css">
    <link rel="stylesheet" href="../css/bill.css">
</head>
<body>

<div class="row expanded">
    <main class="columns">
        <div class="inner-container">
            <header class="row align-center">
                <a class="button hollow secondary" href="shop.php">
                    <i class="ion ion-chevron-left"></i> Quay lại Mua Hàng
                </a>
                &nbsp;&nbsp;
                <a class="button" onclick="window.print();">
                    <i class="ion ion-ios-printer-outline"></i> In Hóa Đơn
                </a>      
            </header>
            <section class="row">
                <div class="callout large invoice-container">
                    <table class="invoice">
                        <tr class="header">
                            <td>
                                <img src="../img/hpl.png" alt="Tên Công Ty" />
                            </td>
                            <td class="align-right">
                                <h2>Hóa Đơn</h2>
                            </td>
                        </tr>
                        <tr class="intro">
                            <td>
                                Xin chào, <?php echo $_SESSION['user']['name']; ?><br>
                                Cảm ơn bạn đã đặt hàng.
                            </td>
                            <td class="text-right">
                                <span class="num">Mã Đơn Hàng #<?php echo $bill_id; ?></span><br>
                                Ngày lập hóa đơn: <?php echo $order_date; ?><br>
                            </td>
                        </tr>
                        <tr class="details">
                            <td colspan="2">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="desc">Mô Tả Sản Phẩm</th>
                                            <th class="qty">Số Lượng</th>
                                            <th class="amt">Giá Gốc</th>
                                            <th class="amt">Giá Khuyến Mãi</th>
                                            <th class="amt">Thành Tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr class="item">
                                                <td class="desc"><?php echo $product['product_name']; ?></td>
                                                <td class="qty"><?php echo $product['quantity']; ?></td>
                                                <td class="amt"><?php echo formatCurrency($product['original_price']); ?></td>
                                                <td class="amt"><?php echo formatCurrency($product['discount_price']); ?></td>
                                                <td class="amt"><?php echo formatCurrency($product['subtotal_price']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td> 
                        </tr>
                        <tr class="totals">
                            <td></td>
                            <td>
                                <table>
                                    <tr class="subtotal">
                                        <td class="num">Tạm Tính</td>
                                        <td class="num"><?php echo formatCurrency($temp_total_amount); ?></td>
                                    </tr>
                                    <tr class="fees">
                                        <td class="num">Phí Vận Chuyển</td>
                                        <td class="num"><?php echo formatCurrency($shipping_fee); ?></td>
                                    </tr>
                                    <tr class="total">
                                        <td>Tổng Cộng</td>
                                        <td><?php echo formatCurrency($final_amount); ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <section class="additional-info">
                        <div class="row">
                            <div class="columns">
                                <h5>Thông Tin Giao Hàng</h5>
                                <?php echo $shipping_address; ?><br>
                            </div>
                            <div class="columns">
                                <h5>Phương Thức Thanh Toán</h5>
                                <p><?php echo $payment_method; ?></p>
                            </div>
                            <div class="columns">
                                <h5>Phương Thức Giao Hàng</h5>
                                <p><?php echo $shipping_method; ?></p>
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>