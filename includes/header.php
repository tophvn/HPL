<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/database.php'; 
include_once __DIR__ . '/../config/config.php'; 

// Kết nối đến cơ sở dữ liệu
$conn = Database::getConnection();
$q1 = $conn->query("SELECT * FROM categories");
$isIndexPage = basename($_SERVER['PHP_SELF']) === 'index.php';

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];

    // Lấy số lượng sản phẩm trong giỏ
    $cart_query = "
        SELECT SUM(cart_item.quantity) AS total_quantity 
        FROM cart 
        JOIN cart_item ON cart.cart_id = cart_item.cart_id 
        WHERE cart.user_id = $user_id
    ";
    $cart_result = $conn->query($cart_query);
    $row = $cart_result->fetch_assoc();
    $cart_count = $row['total_quantity'] ? $row['total_quantity'] : 0; // Đặt về 0 nếu không có sản phẩm

    // Lấy số lượng sản phẩm yêu thích
    $favorites_query = "
        SELECT COUNT(*) AS favorite_count
        FROM favorites
        WHERE user_id = $user_id
    ";
    $favorites_result = $conn->query($favorites_query);
    $favorite_row = $favorites_result->fetch_assoc();
    $favorite_count = $favorite_row['favorite_count'] ? $favorite_row['favorite_count'] : 0; // Đặt về 0 nếu không có sản phẩm yêu thích
} else {
    $cart_count = 0; 
    $favorite_count = 0; 
}
?>

<div class="container-fluid">
    <div class="row bg-secondary py-2 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark" href="">FAQs</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Help</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Support</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                <a class="text-dark px-2" href="">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a class="text-dark px-2" href="">
                    <i class="fab fa-instagram"></i>
                </a>
                <a class="text-dark pl-2" href="">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="<?php echo BASE_URL; ?>index.php" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-success font-weight-bold border px-3 mr-1">HPL</span>Fashion
                </h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form action="<?php echo VIEWS_URL; ?>search.php" method="GET">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Tìm kiếm" required>
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text bg-transparent text-primary" style="border: none; background: none; cursor: pointer;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
            <div class="col-lg-3 col-6 text-right">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?php echo VIEWS_URL; ?>favorites.php" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                    <span class="badge"><?php echo $favorite_count; ?></span> 
                </a>
                <a href="<?php echo VIEWS_URL; ?>cart.php" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge"><?php echo $cart_count; ?></span> 
                </a>            
                <?php if ($_SESSION['user']['roles'] === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>admin/admin.php" class="btn btn-danger">
                        Admin
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo AUTH_URL . '/login.php'; ?>" class="btn border">
                    <i class="fas fa-sign-in-alt text-primary"></i>
                    Đăng Nhập
                </a>
                <a href="<?php echo AUTH_URL . '/register.php'; ?>" class="btn border">
                    <i class="fas fa-user-plus text-primary"></i>
                    Đăng Ký
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Navbar -->
<div class="container-fluid mb-5">
    <!-- <div class="row border-top px-xl-5"> -->
    <div class="container-fluid px-0">
    <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
    <a href="<?php echo BASE_URL; ?>index.php" class="text-decoration-none d-block d-lg-none">
        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">HPL</span>Fashion</h1>
    </a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
    <div class="navbar-nav mx-auto py-0">
        <a href="<?php echo BASE_URL; ?>index.php" class="nav-item nav-link active fw-bold">TRANG CHỦ</a>
        <a href="<?php echo VIEWS_URL; ?>shop.php" class="nav-item nav-link fw-bold">SẢN PHẨM</a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle fw-bold" data-toggle="dropdown">DANH MUC</a>
            <div class="dropdown-menu rounded-0 m-0">
                <a href="<?php echo VIEWS_URL; ?>cart.php" class="dropdown-item fw-bold">GIỎ HÀNG</a>
                <a href="<?php echo VIEWS_URL; ?>checkout.php" class="dropdown-item fw-bold">THANH TOÁN</a>
            </div>
        </div>
        <a href="<?php echo VIEWS_URL; ?>order_history.php" class="nav-item nav-link fw-bold">LỊCH SỬ MUA HÀNG</a>
        <a href="<?php echo VIEWS_URL; ?>contact.php" class="nav-item nav-link fw-bold">LIÊN HỆ</a>
        <a href="#" class="nav-item nav-link fw-bold">TIN TỨC</a>
    </div>
    <div class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle fw-bold" data-toggle="dropdown"><i class="fas fa-user"></i> <?php echo $_SESSION['user']['name']; ?>!</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="<?php echo VIEWS_URL; ?>account.php" class="dropdown-item fw-bold">Tài Khoản</a>
                    <a href="<?php echo AUTH_URL . '/logout.php'; ?>" class="dropdown-item fw-bold">Đăng Xuất</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</nav>
    <div class="col-lg-9 mx-auto">
        <!-- Carousel -->
        <br>
        <?php if ($isIndexPage): ?>
            <div id="header-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="height: 410px;">
                        <img class="img-fluid w-100" src="img/carousel-1.jpg" alt="Hình ảnh">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h4 class="text-light text-uppercase font-weight-medium mb-3">Giao Hàng Nhanh, Miễn Phí Ship</h4>
                                <h3 class="display-4 text-white font-weight-semi-bold mb-4">Sản Phẩm Chất Lượng</h3>
                                <a href="#" class="btn btn-light py-2 px-3">Mua Ngay</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" style="height: 410px;">
                        <img class="img-fluid w-100" src="img/carousel-2.jpg" alt="Hình ảnh">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h4 class="text-light text-uppercase font-weight-medium mb-3">Giảm Giá đến 20% Cho Quần Áo</h4>
                                <h3 class="display-4 text-white font-weight-semi-bold mb-4">Giá Cả Hợp Lý</h3>
                                <a href="#" class="btn btn-light py-2 px-3">Mua Ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>