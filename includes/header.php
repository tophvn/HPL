<?php
include_once __DIR__ . '/../config/database.php'; 
include_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Kết nối đến cơ sở dữ liệu
$conn = Database::getConnection();
$q1 = $conn->query("SELECT * FROM categories");
$isIndexPage = basename($_SERVER['PHP_SELF']) === 'index.php';

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    // Lấy số lượng sản phẩm trong giỏ
    $cart_result = $conn->query("SELECT SUM(quantity) AS total_quantity FROM cart_item JOIN cart ON cart_item.cart_id = cart.cart_id WHERE user_id = $user_id");
    $row = $cart_result->fetch_assoc();
    $cart_count = $row['total_quantity'] ? $row['total_quantity'] : 0; // Đặt về 0 nếu không có sản phẩm

    // Lấy số lượng sản phẩm yêu thích
    $favorites_result = $conn->query("SELECT COUNT(*) AS favorite_count FROM favorites WHERE user_id = $user_id");
    $favorite_row = $favorites_result->fetch_assoc();
    $favorite_count = $favorite_row['favorite_count'] ? $favorite_row['favorite_count'] : 0; // Đặt về 0 nếu không có sản phẩm yêu thích

    // Lấy sản phẩm trong giỏ hàng
    $cart_items_result = $conn->query("
        SELECT cart_item.*, products.product_name, products.image 
        FROM cart_item 
        JOIN cart ON cart_item.cart_id = cart.cart_id 
        JOIN products ON cart_item.product_id = products.product_id 
        WHERE cart.user_id = $user_id
    ");
    
    $cart_items = [];
    while ($item = $cart_items_result->fetch_assoc()) {
        $cart_items[] = $item; // Thêm sản phẩm vào mảng
    }
} else {
    $cart_count = 0; 
    $favorite_count = 0; 
}
if (isset($_POST['remove_from_cart'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user']['user_id'];

    // Lấy cart_id của người dùng
    $cart_result = $conn->query("SELECT cart_id FROM cart WHERE user_id = $user_id");
    if ($cart_result->num_rows > 0) {
        $cart_id = $cart_result->fetch_assoc()['cart_id'];

        // Xóa sản phẩm khỏi giỏ hàng
        $conn->query("DELETE FROM cart_item WHERE cart_id = $cart_id AND product_id = $product_id");
    }
}
?>

<style>
    /* Hiệu ứng dropdown chỉ hiện ra khi hover vào nút */
    .nav-item.dropdown .dropdown-menu {
        display: none; 
        top: auto;
        transform: translateY(15px);
        opacity: 0;
        border-radius: 0.5rem;
        right: 0; 
        left: auto; 
        z-index: 1000;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }
    .nav-item.dropdown:hover .dropdown-menu {
        display: block; 
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    .table {
        border: none;
    }
    .cart-table img {
        border-radius: 0.375rem;
    }
    .cart-box {
        min-width: 300px;
        padding: 1rem;
    }
</style>

<div class="container-fluid">
    <div class="row bg-secondary py-2 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark" href="">FAQs</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">VietNam</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Hỗ Trợ</a>
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
                <div class="nav-item dropdown mini-cart d-inline-block">
                <a class="btn border dropdown-toggle cart-info position-relative" href="#" id="cart-dropdown">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge"><?php echo $cart_count; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end cart-box">
                    <?php if ($cart_count > 0): ?>
                        <div class="table-responsive">
                            <table class="table cart-table align-middle mb-0">
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <tr>
                                            <td >
                                                <img src="<?php echo ASSETS_URL; ?>img_product/<?php echo $item['image']; ?>" alt="<?php echo $item['product_name']; ?>" width="50">
                                            </td>
                                            <td>
                                                <strong><?php echo $item['product_name']; ?></strong>
                                                <br>
                                                <?php echo $item['quantity']; ?> x <?php echo number_format($item['price'], 2); ?> VNĐ
                                            </td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                    <button type="submit" name="remove_from_cart" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex mt-3 justify-content-end">
                            <a href="<?php echo VIEWS_URL; ?>cart.php" class="btn btn-primary btn-sm ms-3">Giỏ Hàng</a>&nbsp &nbsp 
                            <a href="<?php echo VIEWS_URL; ?>checkout.php" class="btn btn-primary btn-sm" >Thanh Toán</a>
                        </div>
                        <?php else: ?>
                    <?php endif; ?>
                </div>
                </div>
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
        <a href="<?php echo VIEWS_URL; ?>order_history.php" class="nav-item nav-link fw-bold">ĐƠN HÀNG</a>
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