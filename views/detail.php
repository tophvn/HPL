<?php
include('../config/Database.php');
// Kiểm tra trạng thái session và khởi động nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu phiên
}

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn để lấy thông tin sản phẩm
$query = "SELECT * FROM products WHERE product_id = $product_id"; // Chèn trực tiếp giá trị vào câu truy vấn
$result = Database::getConnection()->query($query); // Sử dụng phương thức query
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<h2>Product not found</h2>";
    exit;
}

// Kiểm tra và lấy ID người dùng từ session
if (isset($_SESSION['user'])) {
    // Nếu session có key 'user', lấy ra
    $user = $_SESSION['user'];
    $id = isset($user['user_id']) ? $user['user_id'] : 0; // Kiểm tra user_id
} else {
    $id = 0; // Không có user đăng nhập
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href=" " rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Chi Tiết Sản Phẩm</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Chi Tiết Sản Phẩm</p>
            </div>
        </div>
    </div>

    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="assets/images/<?= $product['image'] ?>" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="assets/images/<?= $product['image2'] ?>" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="assets/images/<?= $product['image3'] ?>" alt="Image">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold"><?= htmlspecialchars($product['product_name']) ?></h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(50 Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4"><?= number_format($product['price']) ?> VNĐ</h3>
                <p class="mb-4"><?= htmlspecialchars($product['description']) ?></p>

                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-1" name="size" value="XS">
                            <label class="custom-control-label" for="size-1">XS</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-2" name="size" value="S">
                            <label class="custom-control-label" for="size-2">S</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-3" name="size" value="M">
                            <label class="custom-control-label" for="size-3">M</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-4" name="size" value="L">
                            <label class="custom-control-label" for="size-4">L</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-5" name="size" value="XL">
                            <label class="custom-control-label" for="size-5">XL</label>
                        </div>
                    </form>
                </div>

                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-1" name="color" value="Black">
                            <label class="custom-control-label" for="color-1">Black</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-2" name="color" value="White">
                            <label class="custom-control-label" for="color-2">White</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-3" name="color" value="Red">
                            <label class="custom-control-label" for="color-3">Red</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-4" name="color" value="Blue">
                            <label class="custom-control-label" for="color-4">Blue</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-5" name="color" value="Green">
                            <label class="custom-control-label" for="color-5">Green</label>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center mb-4 pt-2">
                    <form id="addToCartForm" action="addtocart.php" method="get">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus" type="button" onclick="decrementQuantity()">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control bg-secondary text-center" name="quantity" id="quantity" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus" type="button" onclick="incrementQuantity()">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary px-3" type="submit">
                            <i class="fa fa-shopping-cart mr-1"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                        <input type="hidden" name="productId" value="<?= $product_id ?>">
                        <input type="hidden" name="userId" value="<?= $id ?>">
                    </form>
                </div>

                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Chia sẻ:</p>
                    <div class="d-inline-flex">
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
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin theo tab -->
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Mô tả</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Thông tin</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Đánh giá</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Mô tả sản phẩm</h4>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Thông tin bổ sung</h4>
                        <p> comming soon :v</p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-3">
                        <h4 class="mb-3">Đánh giá</h4>
                        <?php
                        $reviewQuery = Database::query("SELECT comments.*, users.name FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE comments.product_id = '$product_id'");
                        if ($reviewQuery->num_rows > 0) {
                            while ($review = $reviewQuery->fetch_array()) {
                                echo '<div class="media mb-4">
                                        <img src="img/user.jpg" alt="Hình ảnh" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                        <div class="media-body">
                                            <h6>' . htmlspecialchars($review['name']) . '<small> - <i>' . htmlspecialchars($review['time_create']) . '</i></small></h6>
                                            <p>' . htmlspecialchars($review['comment_text']) . '.</p>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo '<p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">BẠN CÓ THỂ THÍCH</span></h2>
        </div>
        <div class="row px-xl-5">
            <?php
            // Truy vấn để lấy 7 sản phẩm ngẫu nhiên
            $q1 = Database::query("SELECT products.*, categories.category_name 
                                    FROM products 
                                    JOIN categories ON products.category_id = categories.category_id 
                                    ORDER BY RAND() LIMIT 4");                    
            // Hiển thị sản phẩm
            while ($r1 = $q1->fetch_array()) {
                echo '
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-item border-0">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class="img-fluid w-100" src="assets/images/' . htmlspecialchars($r1['image']) . '" alt="">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">' . htmlspecialchars($r1['product_name']) . '</h6>
                            <div class="d-flex justify-content-center">
                                <h6>' . number_format($r1['price']) . ' VNĐ</h6>
                                <h6 class="text-muted ml-2"><del>' . number_format($r1['price']) . ' VNĐ</del></h6>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <a href="detail.php?id=' . $r1['product_id'] . '" class="btn btn-sm text-dark p-0">
                                <i class="fas fa-eye text-primary mr-1"></i>Xem Chi Tiết
                            </a>
                            <a href="addtocart.php?quantity=1&productId=' . $r1['product_id'] . '&userId=' . $id . '" class="btn btn-sm text-dark p-0">
                                <i class="fas fa-shopping-cart text-primary mr-1"></i>Thêm Vào Giỏ
                            </a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>