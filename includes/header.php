<?php
// Kiểm tra trạng thái session và khởi động nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu session
}

include_once __DIR__ . '/../config/Database.php';
// Kết nối đến cơ sở dữ liệu
$conn = Database::getConnection();
// Lấy danh sách danh mục
$q1 = $conn->query("SELECT * FROM categories");
// Kiểm tra xem trang hiện tại có phải là index.php không
$isIndexPage = basename($_SERVER['PHP_SELF']) === 'index.php';
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
    </div>
<div class="container-fluid">
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="/ShopThoiTrang/index.php" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-success font-weight-bold border px-3 mr-1">HPL</span>Fashion
                </h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form action="">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm">
                    <div class="input-group-append">
                        <span class="input-group-text bg-transparent text-primary">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Navbar -->
<div class="container-fluid mb-5">
    <div class="row border-top px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Danh Mục</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <?php if ($isIndexPage): ?>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 250px">
                        <?php
                        // Lấy danh sách danh mục để hiển thị
                        while ($r1 = $q1->fetch_array()) {
                            echo '<li class="nav-item">';
                            echo '<a href="#' . $r1['category_id'] . '" class="nav-item nav-link">' . $r1['category_name'] . '</a>'; 
                            echo '</li>';
                        }
                        ?>
                    </div>
                </nav>
            <?php endif; ?>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="index.php" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">HPL</span>Fashion</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="/ShopThoiTrang/index.php" class="nav-item nav-link active">Trang Chủ</a>
                        <a href="/ShopThoiTrang/views/shop.php" class="nav-item nav-link">Sản Phẩm</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Danh Mục</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="/ShopThoiTrang/views/cart.php" class="dropdown-item">Giỏ Hàng</a>
                                <a href="/ShopThoiTrang/views/checkout.php" class="dropdown-item">Thanh Toán</a>
                            </div>
                        </div>
                        <a href="/ShopThoiTrang/views/contact.php" class="nav-item nav-link">Liên Hệ</a>
                        <a href="/ShopThoiTrang/views/order_history.php" class="nav-item nav-link">Lịch Sử Mua Hàng</a>
                    </div>
                    <div class="navbar-nav">
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Xin chào, <?php echo $_SESSION['user']['name']; ?>!</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="#" class="dropdown-item">Tài Khoản</a>
                                    <a href="/ShopThoiTrang/views/logout.php" class="dropdown-item">Đăng Xuất</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/ShopThoiTrang/views/login.php" class="nav-item nav-link">Đăng Nhập</a> 
                            <a href="/ShopThoiTrang/views/register.php" class="nav-item nav-link">Đăng Ký</a> 
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
            <?php if ($isIndexPage): ?>
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="/ShopThoiTrang/img/carousel-1.jpg" alt="Hình ảnh">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Giảm Giá 10% Cho Đơn Hàng Đầu Tiên</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Áo Thời Trang</h3>
                                    <a href="#" class="btn btn-light py-2 px-3">Mua Ngay</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="/ShopThoiTrang/img/carousel-2.jpg" alt="Hình ảnh">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Giảm Giá 10% Cho Đơn Hàng Đầu Tiên</h4>
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
