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

// Lấy dữ liệu người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user']['user_id'];
$query = "SELECT address1, address2 FROM users WHERE user_id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Lấy thông tin đơn hàng
$order_query = "SELECT total_amount, payment_method, shipping_method, order_address FROM `order` WHERE user_id = $user_id ORDER BY order_date DESC LIMIT 1";
$order_result = $conn->query($order_query);
$order = $order_result->fetch_assoc();

// Kiểm tra thông tin đơn hàng
$total_amount = $order['total_amount'] ?? 0;
$payment_method = $order['payment_method'] ?? 'Chưa xác định';
$shipping_method = $order['shipping_method'] ?? 'Chưa xác định';
$order_address = $order['order_address'] ?? 'Chưa xác định';

// Tính phí vận chuyển
$shipping_fee = ($shipping_method === 'hỏa tốc') ? 50000 : 0; // 50.000đ cho giao hàng hỏa tốc

// Truy vấn để lấy sản phẩm từ giỏ hàng của người dùng
$query = "SELECT products.product_id, products.product_name, products.price, cart_item.quantity 
          FROM products 
          JOIN cart_item ON products.product_id = cart_item.product_id 
          JOIN cart ON cart_item.cart_id = cart.cart_id 
          WHERE cart.user_id = $user_id";
$result = $conn->query($query);

$products = [];

// Lưu sản phẩm vào mảng
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$order_id = rand(1000, 9999);  // Mã đơn hàng ngẫu nhiên
$order_date = date('F d, Y');  // Ngày hiện tại
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.5.3/css/foundation.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.10-0/ionicons.min.js"></script>
    <link rel="stylesheet" href="../css/bill.css">
</head>
<body>

<div class="row expanded">
  <main class="columns">
    <div class="inner-container">
      <header class="row align-center">
      <a class="button hollow secondary" href="shop.php"><i class="ion ion-chevron-left"></i> Quay lại Mua Hàng</a>
        &nbsp;&nbsp;<a class="button" onclick="window.print();"><i class="ion ion-ios-printer-outline"></i> In Hóa Đơn</a>      
      </header>
      <section class="row">
        <div class="callout large invoice-container">
          <table class="invoice">
            <tr class="header">
              <td class="">
                <img src="../img/hpl.png" alt="Tên Công Ty" />
              </td>
              <td class="align-right">
                <h2>Hóa Đơn</h2>
              </td>
            </tr>
            <tr class="intro">
              <td class="">
                  Xin chào, <?php echo htmlspecialchars($_SESSION['user']['name']); ?><br>
                  Cảm ơn bạn đã đặt hàng.
              </td>
              <td class="text-right">
                <span class="num">Mã Đơn Hàng #<?php echo $order_id; ?></span><br>
                <?php echo $order_date; ?>
              </td>
            </tr>
            <tr class="details">
              <td colspan="2">
                <table>
                  <thead>
                    <tr>
                      <th class="desc">Mô Tả Sản Phẩm</th>
                      <th class="id">Mã Sản Phẩm</th>
                      <th class="qty">Số Lượng</th>
                      <th class="amt">Thành Tiền</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($products as $product): ?>
                      <tr class="item">
                          <td class="desc"><?php echo htmlspecialchars($product['product_name']); ?></td>
                          <td class="id num"><?php echo htmlspecialchars($product['product_id']); ?></td>
                          <td class="qty"><?php echo htmlspecialchars($product['quantity']); ?></td>
                          <td class="amt"><?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?>₫</td>
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
                    <td class="num"><?php echo number_format($total_amount, 0, ',', '.'); ?>₫</td> 
                  </tr>
                  <tr class="fees">
                    <td class="num">Phí Vận Chuyển</td>
                    <td class="num"><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</td> <!-- Hiển thị phí vận chuyển -->
                  </tr>
                  <tr class="total">
                    <td>Tổng Cộng</td>
                    <td><?php echo number_format($total_amount + $shipping_fee, 0, ',', '.'); ?>₫</td> <!-- Cập nhật tổng cộng -->
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <section class="additional-info">
            <div class="row">
              <div class="columns">
                <h5>Thông Tin Thanh Toán</h5>
                  <?php echo htmlspecialchars($order_address); ?><br> <!-- Hiển thị địa chỉ từ đơn hàng -->
                </p>
              </div>
              <div class="columns">
                <h5>Phương Thức Thanh Toán</h5>
                <p><?php echo htmlspecialchars($payment_method); ?></p> <!-- Hiển thị phương thức thanh toán -->
              </div>
              <div class="columns">
                <h5>Phương Thức Giao Hàng</h5>
                <p><?php echo htmlspecialchars($shipping_method); ?></p> <!-- Hiển thị phương thức giao hàng -->
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