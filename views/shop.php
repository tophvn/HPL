<?php
include('../config/Database.php');
session_start();

$limit = 14;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn để lấy sản phẩm
$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = Database::query($sql);

// Truy vấn để đếm tổng số sản phẩm
$totalProducts = Database::query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    $user_id = $_SESSION['user']['user_id'] ?? 0;

    $conn = Database::getConnection();
    $cart_id = $conn->query("SELECT cart_id FROM cart WHERE user_id = $user_id")->fetch_assoc()['cart_id'] ?? null;

    if (!$cart_id) {
        $conn->query("INSERT INTO cart (user_id) VALUES ($user_id)");
        $cart_id = $conn->insert_id;
    }

    $conn->query("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES ($cart_id, $product_id, $quantity)
                  ON DUPLICATE KEY UPDATE quantity = quantity + $quantity");
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=" . $page);
    exit();
}

// thêm sản phẩm vào danh sách yêu thích
if (isset($_POST['add_to_favorites'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['user']['user_id'])) {
        echo "<script type='text/javascript'>alert('Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích.');</script>";
    } else {
        $user_id = $_SESSION['user']['user_id'];
        Database::getConnection()->query("INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)
                      ON DUPLICATE KEY UPDATE id = id");
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=" . $page);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>SHOP THỜI TRANG - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/header.php'; ?>
        <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Sản Phẩm</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Sản Phẩm</p>
            </div>
        </div>
    </div>

    <!-- Shop -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-12">
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Lọc theo giá</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="price-all">
                            <label class="custom-control-label" for="price-all">Tất cả</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-1">
                            <label class="custom-control-label" for="price-1">100K - 500K</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-2">
                            <label class="custom-control-label" for="price-2">500K - 1 Triệu</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-3">
                            <label class="custom-control-label" for="price-3">1 Triệu - 2 Triệu</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-4">
                            <label class="custom-control-label" for="price-4">2 Triệu trở lên</label>
                        </div>
                    </form>
                </div>

                <!-- Brand Filter -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Lọc theo loại</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="color-all">
                            <label class="custom-control-label" for="color-all">Tất cả</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-1">
                            <label class="custom-control-label" for="color-1">ÁO KHOÁC</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-2">
                            <label class="custom-control-label" for="color-2">ÁO SƠ MI</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-3">
                            <label class="custom-control-label" for="color-3">QUẦN JEANS</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-4">
                            <label class="custom-control-label" for="color-4">QUẦN DÀI</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-5">
                            <label class="custom-control-label" for="color-5">HOODIES</label>
                        </div>
                    </form>
                </div>

                <!-- Size Filter -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Lọc theo Size</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="size-all">
                            <label class="custom-control-label" for="size-all">Tất cả</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-1">
                            <label class="custom-control-label" for="size-1">XS</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-2">
                            <label class="custom-control-label" for="size-2">S</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-3">
                            <label class="custom-control-label" for="size-3">M</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-4">
                            <label class="custom-control-label" for="size-4">L</label>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-5">
                            <label class="custom-control-label" for="size-5">XL</label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sản phẩm -->
            <div class="col-lg-9 col-md-12">
                <div class="row">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-item border-0">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    <?php
                                        // Kiểm tra đường dẫn ảnh
                                        $imagePath = (substr($product['image'], 0, 4) == 'http') ? $product['image'] : '../assets/img_product/' . $product['image'];
                                    ?>
                                    <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $product['product_name'] ?>">
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3"><?= $product['product_name'] ?></h6>
                                    <div class="d-flex justify-content-center">
                                        <h6><?= number_format($product['price']) ?> VNĐ</h6>
                                        <?php if (isset($product['old_price']) && $product['old_price'] !== null): ?>
                                            <h6 class="text-muted ml-2"><del><?= number_format($product['old_price']) ?> VNĐ</del></h6>
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
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <!-- Thanh chuyển trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
    <!-- Quay Lại Đầu Trang -->
    <!-- <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a> -->
    <!-- Thư Viện JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
</body>
</html>