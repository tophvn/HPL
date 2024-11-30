<?php
include('../config/database.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Xử lý yêu cầu hủy đơn hàng
if (isset($_POST['cancel_order_id'])) {
    $cancel_order_id = $_POST['cancel_order_id'];
    // Cập nhật trạng thái đơn hàng thành 'Đã hủy' (status_id = 4)
    $cancel_query = "UPDATE `order` SET status_id = 4 WHERE order_id = ?";
    $stmt = $conn->prepare($cancel_query);
    $stmt->bind_param("i", $cancel_order_id);
    $stmt->execute();
    $stmt->close();

    // Thông báo cho người dùng
    echo "<script>alert('Đơn hàng đã được hủy thành công.');</script>";
}

// chuyen trang
$limit = 10; 
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Lấy danh sách đơn hàng của người dùng với phân trang
$query = "SELECT `order`.order_id, `order`.order_date, `order`.order_address, `order`.status_id FROM `order` 
WHERE `order`.user_id = $user_id ORDER BY `order`.order_date DESC 
          LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

// Tổ chức dữ liệu đơn hàng
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']] = $row;
}

// Lấy tổng số đơn hàng để tính toán phân trang
$total_orders_query = "SELECT COUNT(*) AS total FROM `order` WHERE user_id = $user_id";
$total_orders_result = $conn->query($total_orders_query);
$total_orders_row = $total_orders_result->fetch_assoc();
$total_orders = $total_orders_row['total'];
$total_pages = ceil($total_orders / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lịch Sử - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .img-fluid {
            max-width: 100px; 
            height: auto; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .order-detail {
            text-align: left;
            padding: 5px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }
        .order-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .order-item strong {
            font-size: 16px;
            color: #333;
        }

        .order-item span {
            display: block;
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

    </style>
</head>

<body>
    <?php include '../includes/header.php';?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">LỊCH SỬ ĐƠn HÀNG</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Lịch Sử Đơn Hàng</p>
            </div>
        </div>
    </div>
    <section class="h-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày Đặt</th>
                                <th>Địa Chỉ</th>
                                <th>Trạng Thái</th>
                                <th>Chi Tiết</th>
                                <th>Hủy Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo date('F d, Y H:i:s', strtotime($order['order_date'])); ?></td>
                                <td><?php echo $order['order_address']; ?></td>
                                <td>
                                <?php
                                $status_id = $order['status_id'];
                                $status_query = "SELECT status_value, status_color FROM status WHERE status_id = $status_id";
                                $status_result = $conn->query($status_query);
                                $status_row = $status_result->fetch_assoc();
                                $status_value = $status_row['status_value'];
                                $status_color = $status_row['status_color'];
                                echo "<span style='color: $status_color; font-weight: bold;'>$status_value</span>";
                                ?>

                                </td>
                                <td>
                                    <button class="btn btn-info" data-toggle="collapse" data-target="#order-detail-<?php echo $order['order_id']; ?>">Xem Chi Tiết</button>
                                    <div id="order-detail-<?php echo $order['order_id']; ?>" class="collapse order-detail">
                                        <?php
                                        $order_id = $order['order_id'];
                                        $order_details_query = "SELECT p.product_name, od.order_quantity, p.price FROM order_detail od 
                                        JOIN products p ON od.product_id = p.product_id WHERE od.order_id = $order_id";
                                        $order_details_result = $conn->query($order_details_query);
                                        while ($order_detail = $order_details_result->fetch_assoc()) {
                                            echo "<div class='order-item'>
                                                    <strong>" . $order_detail['product_name'] . "</strong>
                                                    <span>Số Lượng: " . $order_detail['order_quantity'] . "</span>
                                                    <span>Giá: " . number_format($order_detail['price'], 0, ',', '.') . " VND</span>
                                                </div>";}
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($status_id == 1): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="cancel_order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Hủy Đơn Hàng</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                    <nav aria-label="Page navigation" class="d-flex justify-content-center">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
