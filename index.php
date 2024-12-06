<?php
include('config/database.php');
session_start();
// add vào danh sách yêu thích
if (isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
        echo "<script type='text/javascript'>
                alert('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích.');
        </script>";
    } else {
        $product_id =$_POST['product_id'];
        $user_id = $_SESSION['user']['user_id'];
        $conn=Database::getConnection();
        $conn->query("INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id) ON DUPLICATE KEY UPDATE id = id");
        header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TRANG CHỦ - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/logo/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>


    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0" style="font-weight: bold;">SẢN PHẨM CHẤT LƯỢNG</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0" style="font-weight: bold;">MIỄN PHÍ VẬN CHUYỂN</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0" style="font-weight: bold;">ĐỔI TRẢ TRONG 7 NGÀY</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0" style="font-weight: bold;">HỖ TRỢ 24/7</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid offer pt-5">
        <div class="row px-xl-5">
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-right text-md-right mb-2 py-5 px-5">
                    <img src="img/offer-1.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-primary mb-3">GIẢM 20% CHO</h5>
                        <h1 class="mb-4 font-weight-semi-bold">PHỤ KIỆN</h1>
                        <a href="views/shop.php" class="btn btn-outline-primary py-md-2 px-md-3" style="font-weight: bold;">Mua Ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-md-left mb-2 py-5 px-5">
                    <img src="img/offer-2.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-primary mb-3">GIẢM 10% CHO</h5>
                        <h1 class="mb-4 font-weight-semi-bold">ÁO OWEN</h1>
                        <a href="views/shop.php" class="btn btn-outline-primary py-md-2 px-md-3" style="font-weight: bold;">Mua Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <?php
            $sql = $conn->query("SELECT categories.*, COUNT(products.product_id) AS product_count FROM categories LEFT JOIN 
            products ON categories.category_id = products.category_id GROUP BY categories.category_id");
            while ($category = $sql->fetch_array()) {?>
                <div class="col-lg-4 col-md-6 pb-1">
                    <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                        <p class="text-right"><?php echo $category['product_count']; ?> Sản Phẩm</p>
                        <a class="cat-img position-relative overflow-hidden mb-3" href="views/collection.php?category_id=<?php echo $category['category_id']; ?>">
                            <img class="img-fluid" src="img/img-collection/<?php echo $category['category_image']; ?>" alt="">
                        </a>
                        <h5 class="font-weight-semi-bold m-0" style="font-weight: bold;"><?php echo $category['category_name']; ?></h5>
                    </div>
                </div>
            <?php
            }
            ?>            
        </div>
    </div>

    <!-- Sản phẩm -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">SẢN PHẨM MỚI</span></h2>
        </div>
        <div class="row px-xl-5 pb-3">
            <?php
            $conn = Database::getConnection(); 
            $sql = "SELECT*FROM products LIMIT 8"; 
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = 'assets/img_product/' . $row['image'];
                    $discount = $row['discount'] ?? 0;
                    $discounted_price = $row['price'] * (1-$discount/100);
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $row['product_name']; ?>">
                                <?php if ($discount>0): ?>
                                    <div class="discount-badge position-absolute top-0 right-0 bg-danger text-white p-2" style="font-size: 14px; font-weight: bold; border-radius: 50%;">
                                        -<?= $discount ?>%
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3" style="font-weight: bold;"><?= $row['product_name']; ?></h6>
                                <div class="d-flex justify-content-center">
                                    <h6 class="text-danger"><?= number_format($discounted_price, 0, ',', '.') ?> VNĐ</h6>
                                    <?php if ($discount > 0): ?>
                                        <h6 class="text-muted ml-2"><del><?= number_format($row['price'], 0, ',', '.') ?> VNĐ</del></h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="views/detail.php?id=<?= $row['product_id']; ?>" class="btn btn-sm text-dark p-0">
                                    <i class="fas fa-eye text-primary mr-1"></i><span class="fw-bold">CHI TIẾT</span>
                                </a>
                                <form method="POST" action="" class="d-flex align-items-center">
                                    <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
                                    <button type="submit" name="add_to_favorites" class="btn btn-sm text-dark p-0 bg-white">
                                        <i class="fas fa-heart text-primary mr-1"></i><span class="fw-bold">YÊU THÍCH</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else { echo '<p class="text-center">Không có sản phẩm nào.</p>';
            }
            $conn->close(); 
            ?>
        </div>

        <div class="px-1 py-5 mx-auto row justify-content-center">
            <div class="card1 full-width pl-4 pl-md-5 pr-3">
                <div class="row">
                    <div class="left-side col-md-6">
                        <br>
                        <h2 class="mb-0"><strong>KHUYẾN MÃI SẬP SÀN THÁNG 12</strong></h2>
                        <button class="btn btn-pink mb-5">XEM NGAY &nbsp;&nbsp;<div class="fa fa-angle-right"></div></button>
                    </div>
                    <div class="right-side col-md-6 row justify-content-center">
                        <div class="d-flex">
                            <img class="girl-img fit-image" src="img/offer-4.png">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card2 full-width pl-4 pl-md-5 pr-3">
                <div class="row px-3">
                    <div class="col-md-12">
                        <div class="blocks row d-flex">
                            <div class="d-flex flex-column">
                                <img class="fit-image img-block" src="img/Nike.jpg">
                                <small class="text-center">Nike</small>
                            </div>
                            <div class="d-flex flex-column">
                                <img class="fit-image img-block" src="img/Adidas.jpg">
                                <small class="text-center">Adidas</small>
                            </div>
                            <div class="d-flex flex-column">
                                <img class="fit-image img-block" src="img/Puma.jpg">
                                <small class="text-center">Puma</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        
        <!-- <div class="text-center mb-4">
            <a href="views/shop.php" class="btn btn-primary">Xem thêm</a>
        </div>
        <div class="image-row" style="display: flex; justify-content: center; padding: 20px; gap: 40px;">
            <img class="banner_coll" src="img/banner_coll/banner_coll_1.png" alt="new" style="flex: 1; width: 100%; max-width: 300px; height: auto;">
            <img class="banner_coll" src="img/banner_coll/banner_coll_2.png" alt="top" style="flex: 1; width: 100%; max-width: 300px; height: auto;">
            <img class="banner_coll" src="img/banner_coll/banner_coll_3.png" alt="sale" style="flex: 1; width: 100%; max-width: 300px; height: auto;">
        </div> -->
            <br>
        <!-- <img class="img-fluid" src="img/banner_coll/banner-1.jpg" alt="Banner" style="width: 100%; height: auto;">         -->
    </div>

    <?php include 'includes/chatbot.php'; ?>
    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>