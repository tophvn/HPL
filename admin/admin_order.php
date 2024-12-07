<?php 
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Thiết lập số lượng đơn hàng hiển thị mỗi trang
$limit = 6;

// Xác định trang hiện tại (nếu không có thì mặc định là trang 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính toán vị trí bắt đầu của dữ liệu
$start = ($page - 1) * $limit;

// Lấy tổng số đơn hàng
$total_orders_sql = "SELECT COUNT(*) AS total FROM `order`";
$total_orders_result = Database::query($total_orders_sql);
$total_orders_row = $total_orders_result->fetch_assoc();
$total_orders = $total_orders_row['total'];

// Lấy dữ liệu đơn hàng với phân trang và sắp xếp
$sql = "SELECT orders.order_id, orders.order_date, orders.order_address, orders.payment_method, orders.total_amount,
        orders.shipping_method, orders.status_id, status.status_value, status.status_color, users.name
        FROM `order` AS orders 
        LEFT JOIN `status` AS status ON orders.status_id = status.status_id 
        LEFT JOIN `users` AS users ON orders.user_id = users.user_id
        ORDER BY orders.order_date DESC
        LIMIT $start, $limit";

$orders = Database::query($sql);

// Lấy danh sách trạng thái
$status_sql = "SELECT * FROM status";
$statuses = Database::query($status_sql);

// Cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status_id = $_POST['status_id'];

    $update_sql = "UPDATE `order` SET status_id = $status_id WHERE order_id = $order_id";
    Database::query($update_sql);
    header("Location: admin_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Đơn Hàng</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>

        <div class="container mt-4">
            <h2>Danh sách Đơn Hàng</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ngày Đặt</th>
                        <th>Người Đặt</th>
                        <th>Địa Chỉ</th>
                        <th>Phương Thức Thanh Toán</th>
                        <th>Tổng Tiền</th>
                        <th>Phương Thức Vận Chuyển</th>
                        <th>Trạng Thái</th>
                        <th>Chi Tiết</th>
                        <th>Cập Nhật</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $statusArray = [];
                while ($status = $statuses->fetch_assoc()) {
                    $statusArray[] = $status;
                }
                while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                        <td><?= $order['name'] ?></td>
                        <td><?= $order['order_address'] ?></td>
                        <td><?= $order['payment_method'] ?></td>
                        <td><?= number_format($order['total_amount'], 2) ?> VND</td>
                        <td><?= $order['shipping_method'] ?></td>
                        <td>
                            <span class="badge" style="background-color: <?= $order['status_color'] ?>; color: white;">
                                <?= $order['status_value'] ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm view-order-details" data-order-id="<?= $order['order_id'] ?>" 
                                    data-name="<?= $order['name'] ?>" 
                                    data-address="<?= $order['order_address'] ?>" 
                                    data-payment="<?= $order['payment_method'] ?>" 
                                    data-amount="<?= number_format($order['total_amount'], 2) ?>" 
                                    data-shipping="<?= $order['shipping_method'] ?>">
                                Chi tiết
                            </button>
                        </td>
                        <td style="width: 200px;">
                            <form method="POST" action="admin_order.php">
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <select name="status_id" class="form-control w-100">
                                    <?php foreach ($statusArray as $status): ?>
                                        <option value="<?= $status['status_id'] ?>" <?= $status['status_id'] == $order['status_id'] ? 'selected' : '' ?>>
                                            <?= $status['status_value'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-warning btn-sm mt-2">Cập nhật</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Hiển thị nút phân trang -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Trước</a>
                    </li>
                    <?php
                    $total_pages = ceil($total_orders / $limit);
                    for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Tiếp theo</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal hiển thị chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsLabel">Chi Tiết Đơn Hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetailsContent"></div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-order-details').on('click', function() {
                var orderId = $(this).data('order-id');
                var name = $(this).data('name');
                var address = $(this).data('address');
                var payment = $(this).data('payment');
                var amount = $(this).data('amount');
                var shipping = $(this).data('shipping');
                var details = `
                    <p><strong>ID Đơn Hàng:</strong> ${orderId}</p>
                    <p><strong>Người Đặt:</strong> ${name}</p>
                    <p><strong>Địa Chỉ:</strong> ${address}</p>
                    <p><strong>Phương Thức Thanh Toán:</strong> ${payment}</p>
                    <p><strong>Tổng Tiền:</strong> ${amount} VND</p>
                    <p><strong>Phương Thức Vận Chuyển:</strong> ${shipping}</p>
                `;
                $('#orderDetailsContent').html(details);
                $('#orderDetailsModal').modal('show');
            });
        });
    </script>
</body>
</html>
