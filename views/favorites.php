<?php
include('../config/database.php');
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php"); // Chuyển hướng tới trang chủ nếu chưa đăng nhập
    exit();
}

$user_id = $_SESSION['user']['user_id'];
$conn = Database::getConnection();

// Kiểm tra nếu có yêu cầu xóa sản phẩm khỏi danh sách yêu thích
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Xóa sản phẩm khỏi danh sách yêu thích
    $sql = "DELETE FROM favorites WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        // Chuyển hướng lại trang danh sách yêu thích sau khi xóa thành công
        header("Location: favorites.php");
        exit();
    } else {
        echo "Xóa sản phẩm thất bại.";
    }
}

// Truy vấn để lấy danh sách sản phẩm yêu thích của người dùng
$sql = "SELECT p.product_id, p.product_name AS name, p.description, p.price, p.image 
        FROM favorites f 
        JOIN products p ON f.product_id = p.product_id 
        WHERE f.user_id = $user_id";
$result = $conn->query($sql);

$favorites = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>YÊU THÍCH - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href=" " rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
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
                    <img src="<?= (substr($product['image'], 0, 4) == 'http') ? $product['image'] : '../assets/img_product/' . $product['image']; ?>" class="card-img-top" alt="<?= $product['name']; ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $product['name']; ?></h5>
                        <p class="card-text text-truncate" style="max-height: 50px; overflow: hidden;"><?= $product['description']; ?></p>
                        <p class="card-text mt-auto">Giá: <?= number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
                        <a href="detail.php?id=<?= $product['product_id']; ?>" class="btn btn-primary mt-2">Xem chi tiết</a>
                    </div>
                    <!-- Nút Xóa khỏi danh sách yêu thích -->
                    <form action="favorites.php" method="POST" class="position-absolute" style="top: 10px; right: 10px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?');">
                        <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm" style="background-color: transparent; border: none; color: red;">
                            <i class="fas fa-times"></i> <!-- Biểu tượng "X" -->
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
