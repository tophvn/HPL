<?php
session_start();
include('../config/Database.php');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user']) || empty($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];
$conn = Database::getConnection();

// Xử lý cập nhật số lượng và xóa sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];

    if ($action === 'update_quantity') {
        $quantity = $_POST['quantity'];
        
        // Tạo câu lệnh SQL để cập nhật số lượng
        $sql = "UPDATE cart_item SET quantity = $quantity WHERE product_id = $product_id 
                AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit();
        
    } elseif ($action === 'remove_item') {
        // Tạo câu lệnh SQL để xóa sản phẩm
        $sql = "DELETE FROM cart_item WHERE product_id = $product_id 
                AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit();
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng
$sql = "SELECT products.product_id, products.product_name, products.price, products.image, cart_item.quantity 
        FROM products 
        JOIN cart_item ON products.product_id = cart_item.product_id 
        JOIN cart ON cart_item.cart_id = cart.cart_id 
        WHERE cart.user_id = $user_id"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Giỏ Hàng - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php 
                        $tong_tien = 0;
                        while ($row = $result->fetch_assoc()): 
                            $tong_tien += $row['price'] * $row['quantity'];
                        ?>
                        <tr id="row-<?= $row['product_id'] ?>">
                            <td class="align-middle">
                                <img src="<?= $row['image'] ?>" alt="" style="width: 50px;"> <?= htmlspecialchars($row['product_name']) ?>
                            </td>
                            <td class="align-middle"><?= number_format($row['price']) ?> VNĐ</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 120px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-sm btn-outline-primary btn-minus" onclick="updateQuantity(<?= $row['product_id'] ?>, 'decrease')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-light text-center" value="<?= $row['quantity'] ?>" id="quantity-<?= $row['product_id'] ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-primary btn-plus" onclick="updateQuantity(<?= $row['product_id'] ?>, 'increase')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle"><?= number_format($row['price'] * $row['quantity']) ?> VNĐ</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger" onclick="removeItem(<?= $row['product_id'] ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="col-lg-4">
            <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Mã giảm giá">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Áp dụng mã</button>
                        </div>
                    </div>
                </form>
                <div class="card border-primary mb-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="font-weight-semi-bold m-0">Tổng Thanh Toán</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Tổng phụ</h6>
                            <h6 class="font-weight-medium"><?= number_format($tong_tien) ?> VNĐ</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Phí giao hàng</h6>
                            <h6 class="font-weight-medium">0 VNĐ</h6>
                        </div>
                    </div>
                    <div class="card-footer border-primary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Tổng cộng</h5>
                            <h5 class="font-weight-bold"><?= number_format($tong_tien + 0) ?> VNĐ</h5>
                        </div>
                        <a href="checkout.php" class="btn btn-block btn-primary my-3 py-3">Tiến Hành Thanh Toán</a>
                    </div>
                </div>
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
                location.reload();  // Reload to update the total
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
                    location.reload(); // Reload to update the total
                }
            });
        }
    }
    </script>
</body>
</html>
