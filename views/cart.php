<?php
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user']['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
$user_id = $_SESSION['user']['user_id'];

// Xử lý cập nhật số lượng và xóa sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];

    if ($action === 'update_quantity') {
        $quantity = $_POST['quantity'];

        if ($quantity < 1) {
            $quantity = 1;
        }

        // cập nhật số lượng
        $sql = "UPDATE cart_item SET quantity = $quantity WHERE product_id = $product_id 
                AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)";
        if (Database::query($sql) === TRUE) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Lỗi khi cập nhật số lượng.";
        }
    } elseif ($action === 'remove_item') {
        // xóa sản phẩm
        $sql = "DELETE FROM cart_item WHERE product_id = $product_id 
                AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)";
        if (Database::query($sql) === TRUE) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Lỗi khi xóa sản phẩm.";
        }
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng với giá từ cart_item
$result = Database::query("SELECT products.product_id, products.product_name, cart_item.price, products.image, cart_item.quantity 
FROM products JOIN cart_item ON products.product_id = cart_item.product_id JOIN cart ON cart_item.cart_id = cart.cart_id 
WHERE cart.user_id = $user_id");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Giỏ Hàng - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-12 mb-5">
                <div class="cart-list">
                    <?php 
                    $tong_tien = 0;
                    while ($row = $result->fetch_assoc()): 
                        $tong_tien += $row['price'] * $row['quantity']; // Tính tổng tiền dựa trên giá từ cart_item
                    ?>
                    <div class="cart-item" id="row-<?= $row['product_id'] ?>">
                        <img src="../assets/img_product/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        <div class="item-details">
                            <h5><?= $row['product_name'] ?></h5>
                            <p><?= number_format($row['price']) ?> VNĐ x <?= $row['quantity'] ?> 
                        </div>
                        <div class="item-actions">
                            <div class="quantity-controls">
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="action" value="update_quantity">
                                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                    <button type="submit" name="quantity" value="<?= $row['quantity'] - 1 ?>"><i class="fa fa-minus"></i></button>
                                    <input type="text" value="<?= $row['quantity'] ?>" readonly>
                                    <button type="submit" name="quantity" value="<?= $row['quantity'] + 1 ?>"><i class="fa fa-plus"></i></button>
                                </form>
                            </div>
                            <form method="POST" action="cart.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?');">
                                <input type="hidden" name="action" value="remove_item">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-8">
                <div class="total-price">Tổng Tiền: <?= number_format($tong_tien) ?> VNĐ</div>
                <a href="checkout.php" class="btn btn-block btn-primary my-3 py-3">Tiến Hành Thanh Toán</a>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
