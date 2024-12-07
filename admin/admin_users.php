<?php 
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$limit = 5;
$page = max(1, (int)($_GET['page'] ?? 1)); // Trang hiện tại
$offset = ($page - 1) * $limit; 

$sql = "SELECT user_id, username, name, email, phonenumber, roles FROM users";
$result = Database::query($sql); 
$bills_result = [];
if (isset($_GET['user_id'])) {
    // Lấy user_id từ URL
    $user_id = $_GET['user_id'];
    $bills_sql = "SELECT bill_id, bill_date, total_amount, payment_method, shipping_method FROM bills WHERE user_id = $user_id";
    $bills_result = Database::query($bills_sql);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Thông tin Khách hàng</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>

        <div class="container mt-4">
            <h1 class="mb-4">Thông tin Khách hàng</h1>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Hóa đơn</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phonenumber']; ?></td>
                            <td>
                                <button class="btn btn-info btn-sm view-bills" data-user-id="<?php echo $user['user_id']; ?>">
                                    <i class="fas fa-receipt"></i> Xem hóa đơn
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có người dùng nào.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal để hiển thị hóa đơn -->
        <div class="modal fade" id="billsModal" tabindex="-1" role="dialog" aria-labelledby="billsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="billsModalLabel">Hóa đơn của Người dùng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped" id="billsTable">
                            <thead>
                                <tr>
                                    <th>ID Hóa đơn</th>
                                    <th>Ngày</th>
                                    <th>Tổng tiền</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Phương thức vận chuyển</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($bills_result) && mysqli_num_rows($bills_result) > 0): ?>
                                <?php while ($bill = mysqli_fetch_assoc($bills_result)): ?>
                                    <tr>
                                        <td><?php echo $bill['bill_id']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($bill['bill_date'])); ?></td>
                                        <td><?php echo number_format($bill['total_amount'], 2); ?> VND</td>
                                        <td><?php echo $bill['payment_method']; ?></td>
                                        <td><?php echo $bill['shipping_method']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có hóa đơn nào.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
                        
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-bills').click(function() {
                var userId = $(this).data('user-id');
                // Gọi lại chính trang để lấy hóa đơn
                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: { user_id: userId },
                    success: function(data) {
                        // Lấy nội dung bảng hóa đơn từ phản hồi
                        var modalContent = $(data).find('#billsTable tbody').html();
                        $('#billsTable tbody').html(modalContent);
                        $('#billsModal').modal('show');
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi lấy hóa đơn.');
                    }
                });
            });
        });
    </script>
</body>
</html>