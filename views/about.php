<?php
include('../config/Database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Về Chúng Tôi - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="../assets/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"> 
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <section class="about-section">
        <h1>Về Chúng Tôi</h1>
        <p>HPL Fashion là thương hiệu thời trang hàng đầu chuyên cung cấp các sản phẩm thời trang phong cách và chất lượng cao. Với sự cam kết về chất lượng và dịch vụ, chúng tôi mong muốn mang đến cho khách hàng những trải nghiệm mua sắm tuyệt vời nhất.</p>
    </section>
    <section class="team-section">
        <div class="container">
            <div class="row">
                <!-- Thành viên 1 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-member">
                        <img src="https://s.net.vn/Clmg" alt="Thành viên 1">
                        <h3>Nguyễn Hoàng Hải</h3>
                        <p>Nhân Viên</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-member">
                        <img src="https://s.net.vn/swUj" alt="Thành viên 2">
                        <h3>Nguyễn Tấn Phát</h3>
                        <p>Giám Đốc Tư Vấn Tâm Lý Tình Cảm</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="team-member">
                        <img src="https://s.net.vn/Z1Az" alt="Thành viên 3">
                        <h3>Lê Ngọc Phi Long</h3>
                        <p>Chủ Tịch Tập Đoàn Quản Trị</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <!-- JavaScript và thư viện -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
