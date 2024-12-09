<?php
include('../config/database.php');
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Xóa sản phẩm yêu thích
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Chuyển đổi ID sản phẩm thành int
    $sql = "DELETE FROM favorites WHERE user_id = $user_id AND product_id = $product_id";
    if (Database::query($sql)) {
        // Thiết lập thông báo thành công vào session
        $_SESSION['message'] = 'Sản phẩm đã được xóa khỏi danh sách yêu thích!';
        $_SESSION['message_type'] = 'success'; 
    } else {
        // Thiết lập thông báo lỗi vào session
        $_SESSION['message'] = 'Xóa sản phẩm thất bại.';
        $_SESSION['message_type'] = 'danger'; 
    }
    header("Location: favorites.php");
    exit();
}

$sql = "SELECT products.product_id, products.product_name AS name, products.description, products.price, products.image 
FROM favorites JOIN products ON favorites.product_id = products.product_id WHERE favorites.user_id = $user_id";
$result = Database::query($sql);

// Khởi tạo mảng để lưu danh sách sản phẩm yêu thích
$favorites = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Yêu Thích - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include('../includes/notification.php'); ?>
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">DANH SÁCH YÊU THÍCH</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Danh Sách Yêu Thích</p>
            </div>
        </div>
    </div>

    <div class="container">
    <?php if (empty($favorites)): ?>
        <p class="text-center">Không có sản phẩm nào trong danh sách yêu thích.</p>
        <?php else: ?>
            <div class="row">
            <?php foreach ($favorites as $product): ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card mb-4 d-flex flex-column position-relative">
                    <a href="detail.php?id=<?= $product['product_id'] ?>">
                        <?php $imagePath = '../assets/img_product/' . $product['image']; ?>
                        <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="">
                    </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $product['name']; ?></h5>
                            <p class="card-text text-truncate" style="max-height: 50px; overflow: hidden;"><?= $product['description']; ?></p>
                            <p class="card-text mt-auto">Giá: <?= number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
                            <a href="detail.php?id=<?= $product['product_id']; ?>" class="btn btn-primary mt-2">Xem chi tiết</a>
                        </div>
                        <form action="favorites.php" method="POST" class="position-absolute" style="top: 10px; right: 10px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?');">
                            <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: transparent; border: none; color: red;">
                                <i class="fas fa-times"></i> 
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
