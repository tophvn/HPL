<nav id="sidebar" class="active">
    <div class="sidebar-header">
        <img src="../img/hpl-fashion.png" alt="logo HPL-Fashion" class="app-logo">
    </div>
    <ul class="list-unstyled components text-secondary">
        <li>
            <a href="admin.php"><i class="fas fa-home"></i> Bảng điều khiển</a>
        </li>
        <li>
            <a href="admin_order.php"><i class="fas fa-box"></i> Đơn hàng</a>
        </li>
        <li>
            <a href="admin_users.php"><i class="fas fa-user-friends"></i> Khách hàng</a>
        </li>
        <li>
            <a href="admin_message.php"><i class="fas fa-comment"></i> Tin nhắn</a>
        </li>
        <li>
            <a href="#shippingmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down"><i class="fas fa-shopping-bag"></i> Sản phẩm</a>
            <ul class="collapse list-unstyled" id="shippingmenu">
                <li>
                    <a href="admin_products.php"><i class="fas fa-angle-right"></i> Danh sách sản phẩm</a>
                </li>
                <li>
                    <a href="admin_edit_product.php"><i class="fas fa-angle-right"></i> Chỉnh sửa sản phẩm</a>
                </li>
            </ul>
        </li>
        <!-- <li>
            <a href="#authmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down"><i class="fas fa-user-shield"></i> Cộng tác viên</a>
            <ul class="collapse list-unstyled" id="authmenu">
                <li>
                    <a href="login.html"><i class="fas fa-lock"></i> Tạo tài khoản</a>
                </li>
                <li>
                    <a href="signup.html"><i class="fas fa-user-plus"></i> Danh sách tài khoản</a>
                </li>
            </ul>
        </li> -->
        <li>
            <a href="admin_notify.php"><i class="fas fa-cog"></i> Cài đặt</a>
        </li>
    </ul>
</nav>
<div id="body" class="active">
<!-- thành phần điều hướng navbar -->
<nav class="navbar navbar-expand-lg navbar-white bg-white">
    <button type="button" id="sidebarCollapse" class="btn btn-light">
        <i class="fas fa-bars"></i><span></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <div class="nav-dropdown">
                    <a href="#" id="nav1" class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-link"></i> <span>Liên kết nhanh</span> <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav1">
                        <ul class="nav-list">
                            <li><a href="../index.php" class="dropdown-item"><i class="fas fa-home"></i> Trang chủ</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a href="" class="dropdown-item"><i class="fas fa-database"></i> Sao lưu</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a href="" class="dropdown-item"><i class="fas fa-user-shield"></i> Vai trò</a></li>
                        </ul>
                    </div>
                </div>
            </li>
                <li class="nav-item dropdown">
                <div class="nav-dropdown">
                    <a href="#" id="nav2" class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> 
                        <span>
                            <?php if (isset($_SESSION['user'])): ?>
                                <?= $_SESSION['user']['name'] ?> 
                            <?php endif; ?>
                        </span>
                        <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nav-link-menu">
                        <ul class="nav-list">
                            <div class="dropdown-divider"></div>
                            <li><a href="../views/auth/logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>