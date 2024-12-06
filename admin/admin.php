<?php
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
$conn = Database::getConnection();

// Truy vấn số lượng đơn hàng mới
$sql_orders = "SELECT COUNT(*) as new_orders FROM `order` WHERE status_id = 1"; 
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
$new_orders = $row_orders['new_orders'];

// Truy vấn tổng doanh thu
$sql_revenue = "SELECT SUM(total_amount) as total_revenue FROM `order`";
$result_revenue = $conn->query($sql_revenue);
$row_revenue = $result_revenue->fetch_assoc();
$total_revenue = $row_revenue['total_revenue'] ?? 0;

// views san pham
$sql_products = "SELECT product_name, view_count FROM products ORDER BY view_count DESC LIMIT 10"; 
$result_products = $conn->query($sql_products);
$labels = [];
$data = [];
while ($row = $result_products->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $data[] = $row['view_count'];
}

// Truy vấn đếm số yêu cầu hỗ trợ
$sql_support_requests = "SELECT COUNT(*) as support_requests FROM contacts"; 
$result_support_requests = $conn->query($sql_support_requests);
$row_support_requests = $result_support_requests->fetch_assoc();
$support_requests = $row_support_requests['support_requests'];
$data = array_reverse($data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản Trị - HPL Fashion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 page-header">
                        <div class="page-pretitle">Tổng quan</div>
                        <h2 class="page-title">Bảng điều khiển</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="icon-big text-center">
                                            <i class="teal fas fa-shopping-cart"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="detail">
                                            <p class="detail-subtitle">Đơn hàng</p>
                                            <span class="number"><?php echo number_format($new_orders); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="fas fa-calendar"></i> Trong tuần này
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="icon-big text-center">
                                            <i class="olive fas fa-money-bill-alt"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="detail">
                                            <p class="detail-subtitle">Doanh thu</p>
                                            <span class="number"><?php echo number_format($total_revenue); ?> ₫</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="fas fa-calendar"></i> Trong tháng này
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="icon-big text-center">
                                            <i class="violet fas fa-eye"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="detail">
                                            <p class="detail-subtitle">Lượt xem trang</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="fas fa-stopwatch"></i> Trong tháng này
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="icon-big text-center">
                                            <i class="orange fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="detail">
                                            <p class="detail-subtitle">Yêu cầu hỗ trợ</p>
                                            <span class="number"><?php echo number_format($support_requests); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="fas fa-envelope-open-text"></i> Trong tuần này
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h3>Sản phẩm được quan tâm nhiều nhất</h3>
                        <canvas id="productViewChart" width="400" height="200"></canvas>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-6">
                        <h3 class="text-primary">Sản phẩm bán chạy nhất</h3>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Truy vấn sản phẩm bán chạy nhất
                                $sql_best_selling_products = "
                                    SELECT bi.product_name, SUM(bi.quantity) AS total_quantity
                                    FROM bill_items bi
                                    GROUP BY bi.product_name
                                    ORDER BY total_quantity DESC
                                    LIMIT 5"; // Lấy 5 sản phẩm bán chạy nhất
                                $result_best_selling_products = $conn->query($sql_best_selling_products);

                                while ($row = $result_best_selling_products->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo number_format($row['total_quantity']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-success">Người dùng mua nhiều nhất</h3>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Số lượng sản phẩm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Truy vấn người dùng mua nhiều nhất
                                $sql_top_buying_users = "
                                    SELECT b.user_id, u.name, SUM(bi.quantity) AS total_products
                                    FROM bills b
                                    JOIN bill_items bi ON b.bill_id = bi.bill_id
                                    JOIN users u ON b.user_id = u.user_id
                                    GROUP BY b.user_id
                                    ORDER BY total_products DESC
                                    LIMIT 5"; 
                                    $result_top_buying_users = $conn->query($sql_top_buying_users);

                                while ($row = $result_top_buying_users->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo number_format($row['total_products']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
        const ctx = document.getElementById('productViewChart').getContext('2d');
        const productViewChart = new Chart(ctx, {
            type: 'line', // Biểu đồ đường
            data: {
                labels: <?php echo json_encode($labels); ?>, // Tên sản phẩm
                datasets: [{
                    label: 'Số lượt xem',
                    data: <?php echo json_encode($data); ?>, // Dữ liệu lượt xem
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true, // Tô màu dưới đường
                    tension: 0.1 
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10 // Tùy chỉnh khoảng cách giữa các giá trị trên trục Y
                        }
                    }
                }
            }
        });
    </script>


    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>