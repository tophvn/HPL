<div class="container-fluid">
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="index.php" class="text-decoration-none">
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
            <nav class="collapse navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 250px">
                    <?php
                    $q1 = Database::query("SELECT categories.* FROM categories");
                    while ($r1 = $q1->fetch_array()) {
                        echo '<li class="nav-item">';
                        echo '<a href="shop.php?category=' . $r1['category_id'] . '" class="nav-item nav-link">' . $r1['category_name'] . '</a>';
                        echo '</li>';
                    }
                    ?>
                </div>
            </nav>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="index.php" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
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
                                <a href="cart.php" class="dropdown-item">Giỏ Hàng</a>
                                <a href="checkout.php" class="dropdown-item">Thanh Toán</a>
                            </div>
                        </div>
                        <a href="/ShopThoiTrang/views/contact.php" class="nav-item nav-link">Liên Hệ</a>
                        <a href="order_history.php" class="nav-item nav-link">Lịch Sử Mua Hàng</a>
                    </div>
                    <div class="navbar-nav">
                        <?php
                        if (isset($_SESSION['user'])) {
                            $name = $_SESSION['name'];
                            echo "Xin chào $name!";
                            echo '<a href="#" class="nav-item nav-link">Đăng Xuất</a>';
                        } else {
                            echo '<a href="#" class="nav-item nav-link">Đăng Nhập</a>';
                            echo '<a href="#" class="nav-item nav-link">Đăng Ký</a>';
                        }
                        ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
