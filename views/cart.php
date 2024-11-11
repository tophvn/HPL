<?php
session_start();
include('../config/Database.php');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user']) || empty($_SESSION['user']['user_id'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển hướng đến trang login
    exit();
}

$user_id = $_SESSION['user']['user_id'];

$conn = Database::getConnection();

// Truy vấn để lấy sản phẩm từ giỏ hàng của người dùng
$sql = "SELECT p.product_id, p.product_name, p.price, p.image, ci.quantity 
        FROM products p 
        JOIN cart_item ci ON p.product_id = ci.product_id 
        JOIN cart c ON ci.cart_id = c.cart_id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>GIỎ HÀNG - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Mua sắm trực tuyến" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Giỏ Hàng Của Bạn</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Giỏ hàng</p>
            </div>
        </div>
    </div>
    <!-- Giỏ Hàng Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php 
                        $tong_tien = 0; // Biến để lưu tổng giá
                        while ($row = $result->fetch_assoc()): 
                            $tong_tien += $row['price'] * $row['quantity']; // Tính tổng giá
                        ?>
                        <tr>
                            <td class="align-middle">
                                <img src="<?= $row['image'] ?>" alt="" style="width: 50px;"> <?= htmlspecialchars($row['product_name']) ?>
                            </td>
                            <td class="align-middle"><?= number_format($row['price']) ?> VNĐ</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 120px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-sm btn-outline-primary btn-minus" onclick="decreaseQuantity(<?= $row['product_id'] ?>)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-light text-center" value="<?= $row['quantity'] ?>" id="quantity-<?= $row['product_id'] ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-primary btn-plus" onclick="increaseQuantity(<?= $row['product_id'] ?>)">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle"><?= number_format($row['price'] * $row['quantity']) ?> VNĐ</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger" onclick="removeItem(<?= $row['product_id'] ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Mã giảm giá">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Áp dụng mã</button>
                        </div>
                    </div>
                </form>
                <div class="card border-primary mb-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="font-weight-semi-bold m-0">Tổng Thanh Toán</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Tổng phụ</h6>
                            <h6 class="font-weight-medium"><?= number_format($tong_tien) ?> VNĐ</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Phí giao hàng</h6>
                            <h6 class="font-weight-medium">10,000 VNĐ</h6>
                        </div>
                    </div>
                    <div class="card-footer border-primary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Tổng cộng</h5>
                            <h5 class="font-weight-bold"><?= number_format($tong_tien + 10000) ?> VNĐ</h5> <!-- Tổng cộng với phí giao hàng -->
                        </div>
                        <button class="btn btn-block btn-primary my-3 py-3">Tiến Hành Thanh Toán</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thư viện JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Tệp Javascript -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>
    <script src="js/main.js"></script>
</body>
    <script>
    function decreaseQuantity(productId) {
        let quantityInput = document.getElementById(`quantity-${productId}`);
        let currentQuantity = parseInt(quantityInput.value);

        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }

    function increaseQuantity(productId) {
        let quantityInput = document.getElementById(`quantity-${productId}`);
        let currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;
    }

    function removeItem(productId) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?")) {
            window.location.href = `remove_from_cart.php?product_id=${productId}`;
        }
    }
    </script>
</html>
