<?php
include('../config/database.php');
session_start();
$conn = Database::getConnection();

// Kiểm tra xem có truy vấn tìm kiếm không
$searchResults = [];
$query = ''; 

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    // Truy vấn trực tiếp với LIKE
    $searchQuery = "SELECT * FROM products WHERE product_name LIKE '%$query%'";
    $result = $conn->query($searchQuery);
    if ($result) {
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tìm Kiếm - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href=" " rel="icon">
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

    <div class="container mt-4">
        <?php if (count($searchResults) == 0): ?>
            <!-- Thêm thanh tìm kiếm chỉ khi không có sản phẩm -->
            <form action="search.php" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="<?= $query; ?>" required>
                    <button type="submit" class="input-group-text bg-transparent text-primary" style="border: none; background: none; cursor: pointer;">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>
        <!-- sản phẩm -->
        <?php if ($query !== ''): ?>
            <h2>KẾT QUẢ CHO: <strong><?= $query; ?></strong></h2>
            <div class="row">
                <?php if (count($searchResults) > 0): ?>
                    <?php foreach ($searchResults as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-item border-0">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    <?php $imagePath = (substr($product['image'], 0, 4) == 'http') ? $product['image'] : '../assets/img_product/' . $product['image']; ?>
                                    <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $product['product_name']; ?>">
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3"><?= $product['product_name']; ?></h6>
                                    <div class="d-flex justify-content-center">
                                        <h6><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</h6>
                                        <?php if (isset($product['old_price'])): ?>
                                            <h6 class="text-muted ml-2"><del><?= number_format($product['old_price'], 0, ',', '.') ?> VNĐ</del></h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between bg-light border">
                                    <a href="detail.php?id=<?= $product['product_id'] ?>" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-eye text-primary mr-1"></i>CHI TIẾT
                                    </a>
                                    <form method="POST" action="" class="d-flex align-items-center">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" name="add_to_favorites" class="btn btn-sm text-dark p-0 bg-white">
                                            <i class="fas fa-heart text-primary mr-1"></i>YÊU THÍCH
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không tìm thấy sản phẩm nào phù hợp.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
