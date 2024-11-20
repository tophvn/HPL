<?php
include('../config/database.php');
session_start();

$limit = 14;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Nhận dữ liệu từ bộ lọc
$conditions = [];
$price_filter = $_GET['price'] ?? []; 
if (!empty($price_filter)) {
    if (in_array("1", $price_filter)) $conditions[] = "(price BETWEEN 100000 AND 500000)";
    if (in_array("2", $price_filter)) $conditions[] = "(price BETWEEN 500001 AND 1000000)";
    if (in_array("3", $price_filter)) $conditions[] = "(price BETWEEN 1000001 AND 2000000)";
    if (in_array("4", $price_filter)) $conditions[] = "(price > 2000000)";
}

$type_filter = $_GET['type'] ?? []; 
if (!empty($type_filter)) {
    foreach ($type_filter as $t) {
        $conditions[] = "type = '$t'";
    }
}

$size_filter = $_GET['size'] ?? [];
if (!empty($size_filter)) {
    foreach ($size_filter as $s) {
        $conditions[] = "size LIKE '%$s%'";
    }
}

$whereClause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

// Xử lý sắp xếp
$sort_by = $_GET['sort_by'] ?? '';
switch ($sort_by) {
    case 'price_asc':
        $orderClause = "ORDER BY price ASC";
        break;
    case 'price_desc':
        $orderClause = "ORDER BY price DESC";
        break;
    case 'name_asc':
        $orderClause = "ORDER BY product_name ASC";
        break;
    case 'name_desc':
        $orderClause = "ORDER BY product_name DESC";
        break;
    default:
        $orderClause = "ORDER BY product_id ASC"; // Sắp xếp mặc định
}

// Truy vấn sản phẩm và tổng số sản phẩm
$result = Database::query("SELECT * FROM products $whereClause $orderClause LIMIT $limit OFFSET $offset");
$totalProducts = Database::query("SELECT COUNT(*) as total FROM products $whereClause")->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user']['user_id'] ?? 0;

    if (isset($_POST['add_to_cart'])) {
        $quantity = $_POST['quantity'] ?? 1;
        $conn = Database::getConnection();
        $cart_id = $conn->query("SELECT cart_id FROM cart WHERE user_id = $user_id")->fetch_assoc()['cart_id'] ?? null;
        if (!$cart_id) {
            $conn->query("INSERT INTO cart (user_id) VALUES ($user_id)");
            $cart_id = $conn->insert_id;
        }
        $conn->query("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES ($cart_id, $product_id, $quantity)
                      ON DUPLICATE KEY UPDATE quantity = quantity + $quantity");
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=$page");
        exit();
    }

    if (isset($_POST['add_to_favorites']) && isset($_SESSION['user']['user_id'])) {
        Database::getConnection()->query("INSERT INTO favorites (user_id, product_id) VALUES ($user_id, $product_id)
                      ON DUPLICATE KEY UPDATE id = id");
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=$page");
        exit();
    } else {
        echo "<script>alert('Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Sản Phẩm - HPL FASHION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        nav[aria-label="Page navigation"] {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .pagination {
            margin: 0;
        }
        .discount-badge {
            top: 10px;
            right: 10px;
            background-color: red; 
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 50%; 
            z-index: 10; 
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <!-- bộ lọc -->
    <div class="filter-toggle d-lg-none">
        <i class="fas fa-bars" style="font-size: 30px; color: #333;"></i>
    </div>

    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Sản Phẩm</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="../index.php">Trang Chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Sản Phẩm</p>
            </div>
        </div>
    </div>

    <!-- Shop -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Bộ lọc (Ẩn trên mobile) -->
            <div class="col-lg-3 col-md-12 filter-container">
                <?php include '../includes/filter.php'; ?>
            </div>
            <!-- Sản phẩm --> 
            <div class="col-lg-9 col-md-12">
                <div class="dropdown ml-8">
                    <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sắp Xếp
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=price_asc">Giá thấp đến cao</a>
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=price_desc">Giá cao đến thấp</a>
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=name_asc">Name A - Z</a>
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=name_desc">Name Z - A</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-item border-0">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    <?php $imagePath = (substr($product['image'], 0, 4) == 'http') ? $product['image'] : '../assets/img_product/' . $product['image']; ?>
                                    <img class="img-fluid w-100" src="<?= $imagePath ?>" alt="<?= $product['product_name'] ?>">

                                    <?php if ($product['discount'] > 0): ?>
                                        <div class="discount-badge position-absolute top-0 right-0 bg-danger text-white p-2" style="font-size: 14px; font-weight: bold; border-radius: 50%;">
                                            -<?= $product['discount'] ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3" style="font-weight: bold;"><?= $product['product_name'] ?></h6>
                                    <div class="d-flex justify-content-center">
                                        <?php 
                                        $discount = $product['discount']; 
                                        $discounted_price = $product['price'] * (1 - $discount / 100);
                                        ?>
                                        <h6 class="text-danger"><?= number_format($discounted_price) ?> VNĐ</h6>
                                        <?php if ($discount > 0): ?>
                                            <h6 class="text-muted ml-2"><del><?= number_format($product['price']) ?> VNĐ</del></h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between bg-light border">
                                    <a href="detail.php?id=<?= $product['product_id'] ?>" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-eye text-primary mr-1"></i><span class="fw-bold">CHI TIẾT</span>
                                    </a>
                                    <form method="POST" action="" class="d-flex align-items-center">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" name="add_to_favorites" class="btn btn-sm text-dark p-0 bg-white">
                                            <i class="fas fa-heart text-primary mr-1"></i><span class="fw-bold">YÊU THÍCH</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <!-- Thanh chuyển trang -->
        <nav aria-label="Page navigation" class="d-flex justify-content-center">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".filter-toggle").click(function() {
                $(".filter-container").toggleClass("open");
            });
        });
    </script>
</body>
</html>