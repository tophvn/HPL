<?php
include('../config/database.php');
// Kiểm tra xem category_id có trong URL không
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Lấy tên danh mục dựa trên category_id
$category_query = Database::query("SELECT category_name FROM categories WHERE category_id = $category_id");
$category = $category_query->fetch_array();

if (!$category) {
    echo "Danh mục không tồn tại.";
    exit();
}

$category_name = $category['category_name']; // không sử dụng htmlspecialchars

// Lấy sản phẩm theo category_id
$product_query = Database::query("SELECT * FROM products WHERE category_id = $category_id");
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <title>Bộ Sưu Tập - <?= $category_name ?> - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href=" " rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .discount-badge {
            top: 10px;
            right: 10px;
            background-color: red; 
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 50%; 
            z-index: 10; 
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container-fluid pt-5">
        <h2 class="text-center mb-4">Danh Mục: <?= $category_name ?></h2>
        <div class="row px-xl-5 pb-3">
            <?php if ($product_query->num_rows > 0): ?>
                <?php while ($product = $product_query->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <?php $imagePath = (substr($product['image'], 0, 4) == 'http') ? $product['image'] : '../assets/img_product/' . $product['image']; ?>
                                <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $product['product_name'] ?>">

                                <?php if ($product['discount'] > 0): ?>
                                    <div class="discount-badge position-absolute">
                                        -<?= $product['discount'] ?>%
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3"><?= $product['product_name'] ?></h6>
                                <div class="d-flex justify-content-center">
                                    <?php 
                                    $discount = $product['discount']; 
                                    $discounted_price = $product['price'] * (1 - $discount / 100);
                                    ?>
                                    <h6 class="text-danger"><?= number_format($discounted_price) ?> VNĐ</h6>
                                    <?php if ($discount > 0): ?>
                                        <h6 class="text-muted ml-2"><del><?= number_format($product['price']) ?> VNĐ</del></h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="detail.php?id=<?= $product['product_id'] ?>" class="btn btn-sm text-dark p-0">
                                    <i class="fas fa-eye text-primary mr-1"></i>XEM CHI TIẾT
                                </a>
                                <form method="POST" action="" class="d-flex align-items-center">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" name="add_to_favorites" class="btn btn-sm text-dark p-0 bg-white">
                                        <i class="fas fa-heart text-primary mr-1"></i>THÊM YÊU THÍCH
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Không có sản phẩm nào trong danh mục này.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>