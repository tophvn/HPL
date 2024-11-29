<?php 
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
$sql = "SELECT * FROM products";
$result = Database::query($sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>

        <div class="container mt-4">
            <h1 class="mb-4">Danh Sách Sản Phẩm</h1>
            <div class="row">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($result)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo '../assets/img_product/' . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                                    <p class="card-text">Giá: <?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#productModal<?php echo $product['product_id']; ?>">Chi tiết</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="productModal<?php echo $product['product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="productModalLabel<?php echo $product['product_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="productModalLabel<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="<?php echo '../assets/img_product/' . $product['image']; ?>" class="img-fluid" alt="<?php echo $product['product_name']; ?>">
                                        <?php if ($product['image2']): ?>
                                            <img src="<?php echo '../assets/img_product/' . $product['image2']; ?>" class="img-fluid mt-3" alt="<?php echo $product['product_name']; ?>">
                                        <?php endif; ?>
                                        <?php if ($product['image3']): ?>
                                            <img src="<?php echo '../assets/img_product/' . $product['image3']; ?>" class="img-fluid mt-3" alt="<?php echo $product['product_name']; ?>">
                                        <?php endif; ?>
                                        <p><strong>Giá: </strong><?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                                        <p><strong>Mô tả: </strong><?php echo nl2br($product['description']); ?></p>
                                        <p><strong>Size: </strong><?php echo nl2br($product['size']); ?></p>
                                        <p><strong>Giảm giá: </strong><?php echo $product['discount'] > 0 ? $product['discount'] . '%' : 'Không có'; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Không có sản phẩm nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>