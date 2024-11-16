<?php
include('../config/database.php');
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

// Lấy kết nối từ lớp Database
$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Lấy danh sách đơn hàng của người dùng
$query = "SELECT `order`.order_id, `order`.order_date, `order`.order_address, 
                 order_detail.product_id, order_detail.order_quantity, 
                 products.product_name, cart_item.price, products.image 
          FROM `order` 
          JOIN order_detail ON `order`.order_id = order_detail.order_id 
          JOIN products ON order_detail.product_id = products.product_id 
          JOIN cart_item ON order_detail.product_id = cart_item.product_id
          WHERE `order`.user_id = $user_id 
          ORDER BY `order`.order_date DESC";

$result = $conn->query($query);

// Tổ chức dữ liệu đơn hàng
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']]['order_date'] = $row['order_date'];
    $orders[$row['order_id']]['order_address'] = $row['order_address'];
    $orders[$row['order_id']]['products'][] = [
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'quantity' => $row['order_quantity'],
        'price' => $row['price'], // Lấy giá từ cart_item
        'image' => $row['image']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lịch Sử - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href=" " rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .img-fluid {
            max-width: 100px; 
            height: auto; 
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php';?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">LỊCH SỬ MUA HÀNG</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Lịch Sử Mua Hàng</p>
            </div>
        </div>
    </div>

    <section class="h-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <?php foreach ($orders as $order_id => $order): ?>
                    <div class="card mb-4" style="border-radius: 10px;">
                        <div class="card-header px-4 py-5">
                            <h5 class="text-muted mb-0">Ngày Đặt: <span style="color: #a8729a;"><?php echo htmlspecialchars(date('F d, Y H:i:s', strtotime($order['order_date']))); ?></span></h5>
                        </div>
                        <div class="card-body p-4">
                            <?php 
                            $total_amount = 0; // Khởi tạo biến tổng tiền cho đơn hàng
                            foreach ($order['products'] as $product): 
                                $total_amount += $product['quantity'] * $product['price']; // Tính tổng tiền
                            ?>
                            <div class="card shadow-0 border mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="../assets/img_product/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                        </div>
                                        <div class="col-md-5 text-center d-flex flex-column justify-content-center">
                                            <h5 class="text-muted"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                            <p class="text-muted">Số Lượng: <?php echo htmlspecialchars($product['quantity']); ?></p>
                                        </div>
                                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                                            <h5 class="text-muted"><?php echo number_format($product['quantity'] * $product['price'], 0, ',', '.'); ?>₫</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <h5 class="text-muted">Tổng Tiền: <span style="color: #a8729a;"><?php echo number_format($total_amount, 0, ',', '.'); ?>₫</span></h5>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>