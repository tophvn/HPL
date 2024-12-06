<?php 
include('../config/database.php');
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Pagination setup
$limit = 15; // Số sản phẩm trên mỗi trang
$page = max(1, (int)($_GET['page'] ?? 1)); // Trang hiện tại
$offset = ($page - 1) * $limit; // Tính toán offset

$total_sql = "SELECT COUNT(*) AS total FROM products"; // Truy vấn tổng số sản phẩm
$total_result = Database::query($total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total']; // Tổng số sản phẩm

$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset"; // Truy vấn sản phẩm cho trang hiện tại
$result = Database::query($sql); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Danh Sách Sản Phẩm</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>

        <div class="container mt-4">
            <h1 class="mb-4 text-center">Danh Sách Sản Phẩm</h1>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Mô Tả</th>
                            <th>Giảm Giá</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><img src="<?php echo '../assets/img_product/' . ($product['image']); ?>" class="img-fluid" style="max-width: 100px;" alt="<?php echo htmlspecialchars($product['product_name']); ?>"></td>
                                <td><?php echo ($product['product_name']); ?></td>
                                <td><?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                                <td><?php echo nl2br(htmlspecialchars($product['description'])); ?></td>
                                <td><?php echo $product['discount'] > 0 ? htmlspecialchars($product['discount']) . '%' : 'Không có'; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#productModal<?php echo $product['product_id']; ?>">Chi tiết</button>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="productModal<?php echo $product['product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="productModalLabel<?php echo $product['product_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="productModalLabel<?php echo $product['product_id']; ?>"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div id="carouselExample<?php echo $product['product_id']; ?>" class="carousel slide" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item active">
                                                                <img src="<?php echo '../assets/img_product/' . htmlspecialchars($product['image']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                                            </div>
                                                            <?php if ($product['image2']): ?>
                                                                <div class="carousel-item">
                                                                    <img src="<?php echo '../assets/img_product/' . htmlspecialchars($product['image2']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($product['image3']): ?>
                                                                <div class="carousel-item">
                                                                    <img src="<?php echo '../assets/img_product/' . htmlspecialchars($product['image3']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carouselExample<?php echo $product['product_id']; ?>" role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Trở lại</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carouselExample<?php echo $product['product_id']; ?>" role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Tiếp theo</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h3>Thông Tin Sản Phẩm</h3>
                                                    <p><strong>Giá: </strong><?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                                                    <p><strong>Mô tả: </strong><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                                                    <p><strong>Size: </strong><?php echo htmlspecialchars($product['size']); ?></p>
                                                    <p><strong>Màu sắc: </strong><?php echo nl2br(htmlspecialchars($product['color'])); ?></p>
                                                    <p><strong>Giảm giá: </strong><?php echo $product['discount'] > 0 ? htmlspecialchars($product['discount']) . '%' : 'Không có'; ?></p>
                                                    <p><strong>Số lượt xem: </strong><?php echo htmlspecialchars($product['view_count']); ?></p>
                                                    <p><strong>Ngày tạo: </strong><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php 
                $total_pages = ceil($total_products / $limit); // Tính tổng số trang
                ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Trước</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Tiếp theo</a>
                        </li>
                    </ul>
                </nav>
            <?php else: ?>
                <p class="text-center">Không có sản phẩm nào.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>