<?php
include_once('../config/database.php');
session_start();

$limit = 16;
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

// Nhận dữ liệu bộ lọc loại sản phẩm
$type_filter = $_GET['type'] ?? [];
if (!empty($type_filter)) {
    foreach ($type_filter as $t) {
        $conditions[] = "subcategory_id = '$t'"; // Sử dụng subcategory_id
    }
}

// Nhận dữ liệu bộ lọc kích thước
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

// Xử lý thêm sản phẩm vào danh sách yêu thích
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user']['user_id'] ?? 0;

    if (isset($_POST['add_to_favorites']) && isset($_SESSION['user']['user_id'])) {
        Database::addToFavorites($user_id, $product_id);
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=$page");
        exit();
    } else {
        echo "<script>alert('Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sản Phẩm - HPL FASHION</title>
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <!-- Bộ lọc -->
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

    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Bộ lọc trên mobile -->
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
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=name_asc">Tên A - Z</a>
                        <a class="dropdown-item" href="?page=<?php echo $page; ?>&sort_by=name_desc">Tên Z - A</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <?php include 'product_item.php'; ?>
                    <?php endwhile; ?>
                </div>

                <div class="col-12 d-flex justify-content-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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