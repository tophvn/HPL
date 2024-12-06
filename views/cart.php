<?php
session_start();
include('../config/database.php');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user']) || empty($_SESSION['user']['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Xử lý cập nhật số lượng và xóa sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];

    if ($action === 'update_quantity') {
        $quantity = $_POST['quantity'];
        // Tạo câu lệnh SQL để cập nhật số lượng
        if ($conn->query("UPDATE cart_item SET quantity = $quantity WHERE product_id = $product_id 
                          AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)") === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit();
        
    } elseif ($action === 'remove_item') {
        // Tạo câu lệnh SQL để xóa sản phẩm
        if ($conn->query("DELETE FROM cart_item WHERE product_id = $product_id 
                          AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)") === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit();
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng với giá từ cart_item
$result = $conn->query("SELECT products.product_id, products.product_name, cart_item.price, products.image, cart_item.quantity 
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
    <style>
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            background-color: #fff;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .cart-item img {
            width: 80px;
            border-radius: 5px;
        }
        .cart-item .item-details {
            flex-grow: 1;
            margin-left: 20px;
        }
        .cart-item .item-actions {
            display: flex;
            align-items: center;
        }
        .cart-item .item-actions button {
            margin-left: 15px;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-controls button {
            border: 1px solid #ddd;
            background-color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
            border-radius: 5px;
        }
        .total-price {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 18px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
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
                            <h5><?= htmlspecialchars($row['product_name']) ?></h5>
                            <p><?= number_format($row['price']) ?> VNĐ x <?= $row['quantity'] ?> 
                        </div>
                        <div class="item-actions">
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(<?= $row['product_id'] ?>, 'decrease')"><i class="fa fa-minus"></i></button>
                                <input type="text" value="<?= $row['quantity'] ?>" id="quantity-<?= $row['product_id'] ?>" readonly>
                                <button onclick="updateQuantity(<?= $row['product_id'] ?>, 'increase')"><i class="fa fa-plus"></i></button>
                            </div>
                            <button class="btn btn-sm btn-danger" onclick="removeItem(<?= $row['product_id'] ?>)">
                                <i class="fa fa-trash"></i>
                            </button>
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateQuantity(productId, action) {
        let quantityInput = document.getElementById(`quantity-${productId}`);
        let currentQuantity = parseInt(quantityInput.value);

        let newQuantity = action === 'increase' ? currentQuantity + 1 : currentQuantity - 1;
        if (newQuantity < 1) return;

        $.post('cart.php', {
            action: 'update_quantity',
            product_id: productId,
            quantity: newQuantity
        }, function(response) {
            let data = JSON.parse(response);
            if (data.status === 'success') {
                quantityInput.value = newQuantity;
                location.reload();  // Reload để cập nhật tổng
            }
        });
    }

    function removeItem(productId) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?")) {
            $.post('cart.php', {
                action: 'remove_item',
                product_id: productId
            }, function(response) {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                    document.getElementById(`row-${productId}`).remove();
                    location.reload(); // Reload để cập nhật tổng
                }
            });
        }
    }
    </script>
</body>
</html>
