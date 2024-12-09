<?php
include('../config/database.php');
session_start();

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$category_query = Database::query("SELECT category_name FROM categories WHERE category_id = $category_id");
$category = $category_query->fetch_assoc();
if (!$category) {
    echo "Danh mục không tồn tại.";
    exit();
}
$category_name = $category['category_name']; 
$product_query = Database::query("SELECT * FROM products WHERE category_id = $category_id");
// Thêm sản phẩm vào yêu thích
if (isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
        // Thiết lập thông báo lỗi trong session
        $_SESSION['message'] = 'Vui lòng đăng nhập để thêm sản phẩm vào yêu thích.';
        $_SESSION['message_type'] = 'danger'; 
    } else {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user']['user_id'];
        Database::addToFavorites($user_id, $product_id);
        $_SESSION['message'] = 'Sản phẩm đã được thêm vào yêu thích!';
        $_SESSION['message_type'] = 'success'; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bộ Sưu Tập - <?= $category_name ?> - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include('../includes/notification.php'); ?>
    <div class="container-fluid pt-5">
        <h2 class="text-center mb-4">Danh Mục: <?= $category_name ?></h2>
        <div class="row px-xl-5 pb-3">
            <?php if ($product_query->num_rows > 0): ?>
                <?php while ($product = $product_query->fetch_assoc()): ?>
                    <?php 
                        // Include the product item template for each product
                        include 'product_item.php';
                    ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Không có sản phẩm nào trong danh mục này.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
