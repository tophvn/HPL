<?php
include('../config/database.php');
session_start();
$product_id = $_GET['id'] ?? 0;
$conn = Database::getConnection();
$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($query);
if ($result->num_rows === 0) {
    exit; 
}

$product = $result->fetch_assoc();
$conn->query("UPDATE products SET view_count = view_count + 1 WHERE product_id = $product_id");

$user_id = $_SESSION['user']['user_id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_id === 0) {
        echo "<script>alert('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!');</script>";
    } else {
        $quantity = intval($_POST['quantity'] ?? 1);
        $size = $_POST['size'] ?? '';
        $color = $_POST['color'] ?? '';
        
        // Tính giá sau khi giảm giá
        $discount = $product['discount'] ?? 0; 
        $discounted_price = $product['price'] * (1 - $discount / 100);
        $size = !empty($size) ? reset($size) : '';

        // Kiểm tra xem người dùng đã có giỏ hàng chưa
        $result = $conn->query("SELECT cart_id FROM cart WHERE user_id = $user_id");
        if ($result->num_rows === 0) {
            $conn->query("INSERT INTO cart (user_id) VALUES ($user_id)");
            $cart_id = $conn->insert_id;
        } else {
            $cart_id = $result->fetch_assoc()['cart_id'];
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $result = $conn->query("SELECT * FROM cart_item WHERE cart_id = $cart_id AND product_id = $product_id AND size = '$size' AND color = '$color'");
        if ($result->num_rows > 0) {
            $conn->query("UPDATE cart_item SET quantity = quantity + $quantity WHERE cart_id = $cart_id AND product_id = $product_id AND size = '$size' AND color = '$color'");
        } else {
            $conn->query("INSERT INTO cart_item (cart_id, product_id, quantity, size, color, price) VALUES ($cart_id, $product_id, $quantity, '$size', '$color', $discounted_price)");
        }
        echo "<script>alert('Sản phẩm đã được thêm vào giỏ hàng!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $product['product_name']; ?> - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        <div class="carousel-item active" id="image-1">
                            <img class="w-100 h-100" src="../assets/img_product/<?= $product['image'] ?>" alt="Image">
                        </div>
                        <div class="carousel-item" id="image-2">
                            <img class="w-100 h-100" src="../assets/img_product/<?= $product['image2'] ?>" alt="Image">
                        </div>
                        <div class="carousel-item" id="image-3">
                            <img class="w-100 h-100" src="../assets/img_product/<?= $product['image3'] ?>" alt="Image">
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
                <h3 class="font-weight-semi-bold"><?= $product['product_name'] ?></h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(10 Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">
                    <?php 
                    $discount = $product['discount'] ?? 0;
                    $discounted_price = $product['price'] * (1-$discount/100);
                    ?>
                    <span class="text-danger"><?= number_format($discounted_price) ?> VNĐ</span>
                    <?php if ($discount>0): ?>
                        <span class="text-muted ml-2"><del><?= number_format($product['price']) ?> VNĐ</del></span>
                    <?php endif; ?>
                </h3>
                <p class="mb-4"><?= $product['description'] ?></p>

                <form method="POST" id="addToCartForm">
                <?php
                // Biến product chứa thông tin sản phẩm hiện tại
                $product_id = $product['product_id'];
                $sizeString = Database::query("SELECT size FROM products WHERE product_id = $product_id")->fetch_assoc()['size'];
                // Tách chuỗi thành mảng
                $sizes = !empty($sizeString) ? array_map('trim', explode(',', $sizeString)) : [];
                ?>
                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Kích thước:</p>
                    <?php if ($sizes): ?>
                        <?php foreach ($sizes as $index => $size): ?>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="size-<?= $index ?>" name="size[]" value="<?= $size ?>">
                                <label class="custom-control-label" for="size-<?= $index ?>"><?= $size ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Màu sắc:</p>
                    <?php
                    $colors = !empty($product['color']) ? array_map('trim', explode(',', $product['color'])) : [];
                    foreach ($colors as $index => $color):
                    ?>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-<?= $index ?>" name="color" value="<?= $color ?>" data-image="image-<?= $index + 1 ?>" required>
                            <label class="custom-control-label" for="color-<?= $index ?>"><?= $color ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group" style="width: 130px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity()">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity()">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary px-3 ml-3" type="submit">
                        <i class="fa fa-shopping-cart mr-1"></i> THÊM VÀO GIỎ HÀNG
                    </button>
                </div>
            </form>

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
                        <p><?= $product['description'] ?></p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Chính sách đổi trả</h4>
                        <p>Xin lưu ý, tất cả các mặt hàng giảm giá được mua từ ngày 8 tháng 11 đến ngày 1 tháng 1 chỉ được ĐỔI TRẢ.</p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-3">
                    <h4 class="mb-3">Đánh giá</h4>
                    <p>Chưa có đánh giá nào</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products đề xuất -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">BẠN CÓ THỂ THÍCH</span></h2>
        </div>
        <div class="row px-xl-5">
            <?php
            $sql = $conn->query("SELECT products.*, categories.category_name FROM products JOIN categories 
            ON products.category_id = categories.category_id ORDER BY RAND() LIMIT 4");
            while ($row = $sql->fetch_array()) {
                $imagePath = '../assets/img_product/' . $row['image'];
                $discount = $row['discount']; 
                $discounted_price = $row['price']*(1-$discount/100);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-item border-0">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $row['product_name'] ?>">
                            <?php if ($discount > 0): ?>
                                <div class="discount-badge position-absolute top-0 right-0 bg-danger text-white p-2" style="font-size: 14px; font-weight: bold; border-radius: 50%;">
                                    -<?= $discount ?>%
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3"><?= $row['product_name'] ?></h6>
                            <div class="d-flex justify-content-center">
                                <h6 class="text-danger"><?= number_format($discounted_price) ?> VNĐ</h6>
                                <?php if ($discount > 0): ?>
                                    <h6 class="text-muted ml-2"><del><?= number_format($row['price']) ?> VNĐ</del></h6>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center bg-light border">
                            <a href="detail.php?id=<?= $row['product_id'] ?>" class="btn btn-sm text-dark p-0">
                                <i class="fas fa-eye text-primary mr-1"></i>Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        function incrementQuantity() {
            var quantity = document.getElementById("quantity");
            quantity.value = parseInt(quantity.value) + 1;
        }

        function decrementQuantity() {
            var quantity = document.getElementById("quantity");
            if (parseInt(quantity.value) > 1) {
                quantity.value = parseInt(quantity.value) - 1;
            }
        }
    </script>
    <script>
    // JavaScript để thay đổi hình ảnh trong carousel khi chọn màu sắc
    document.querySelectorAll('input[name="color"]').forEach(function(colorRadio) {
        colorRadio.addEventListener('change', function() {
            var selectedImage = document.getElementById(this.getAttribute('data-image'));
            
            // Ẩn tất cả các ảnh
            document.querySelectorAll('.carousel-item').forEach(function(item) {
                item.classList.remove('active');
            });
            // Hiển thị ảnh tương ứng với màu đã chọn
            selectedImage.classList.add('active');
        });
    });
</script>
</body>
</html>