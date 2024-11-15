<?php
include('config/Database.php');
session_start();

// thêm sản phẩm vào danh sách yêu thích
if (isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
        echo "<script type='text/javascript'>
                alert('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích.');
              </script>";
    } else {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user']['user_id'];
        $conn = Database::getConnection();
        $conn->query("INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)
                      ON DUPLICATE KEY UPDATE id = id");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TRANG CHỦ - HPL FASHION</title>
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
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">SẢN PHẨM CHẤT LƯỢNG</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">MIỄN PHÍ VẬN CHUYỂN</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">ĐỔI TRẢ TRONG 7 NGÀY</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">HỖ TRỢ 24/7</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Offer Start -->
    <div class="container-fluid offer pt-5">
        <div class="row px-xl-5">
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                    <img src="img/offer-1.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">Giảm 20% cho</h5>
                        <h1 class="mb-4 font-weight-semi-bold">Bộ Sưu Tập Mùa Xuân</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Mua Ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-left text-white mb-2 py-5 px-5">
                    <img src="img/offer-2.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">Giảm 20% cho</h5>
                        <h1 class="mb-4 font-weight-semi-bold">Bộ Sưu Tập Mùa Đông</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Mua Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">NEW</span></h2>
        </div>
        <div class="row px-xl-5 pb-3">
            <?php
            $conn = Database::getConnection(); 
            $sql = "SELECT * FROM products LIMIT 8"; 
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = (substr($row['image'], 0, 4) == 'http') ? $row['image'] : 'assets/img_product/' . $row['image'];
                    // Tính giá sau khuyến mãi
                    $discount = $row['discount'] ?? 0; // Phần trăm khuyến mãi
                    $discounted_price = $row['price'] * (1 - $discount / 100);
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $row['product_name']; ?>">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3"><?= $row['product_name']; ?></h6>
                                <div class="d-flex justify-content-center">
                                    <h6 class="text-danger"><?= number_format($discounted_price, 0, ',', '.') ?> VNĐ</h6>
                                    <?php if ($discount > 0): ?>
                                        <h6 class="text-muted ml-2"><del><?= number_format($row['price'], 0, ',', '.') ?> VNĐ</del></h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="views/detail.php?id=<?= $row['product_id']; ?>" class="btn btn-sm text-dark p-0">
                                    <i class="fas fa-eye text-primary mr-1"></i>XEM CHI TIẾT
                                </a>
                                <form method="POST" action="" class="d-flex align-items-center">
                                    <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
                                    <button type="submit" name="add_to_favorites" class="btn btn-sm text-dark p-0 bg-white">
                                        <i class="fas fa-heart text-primary mr-1"></i>THÊM YÊU THÍCH
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-center">Không có sản phẩm nào.</p>';
            }
            $conn->close(); 
            ?>
        </div>
        <div class="text-center mb-4">
            <a href="views/shop.php" class="btn btn-primary">Xem thêm</a>
        </div>
    </div>
        
    <!-- <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-1.png" alt="Nhà cung cấp 1">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-2.png" alt="Nhà cung cấp 2">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-3.png" alt="Nhà cung cấp 3">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-4.png" alt="Nhà cung cấp 4">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-5.png" alt="Nhà cung cấp 5">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-6.png" alt="Nhà cung cấp 6">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-7.png" alt="Nhà cung cấp 7">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="img/img-vendors/v-8.png" alt="Nhà cung cấp 8">
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Footer-->
    <?php include 'includes/footer.php'; ?>
    <!-- Quay Lại Đầu Trang -->
    <!-- <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a> -->
    <!-- Thư Viện JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>