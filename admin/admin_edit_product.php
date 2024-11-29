<?php
include('../config/database.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $discount = $_POST['discount'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $type_id = $_POST['type_id'];

    // Kiểm tra giá trị price
    if (!is_numeric($price) || $price < 0) {
        $errorMessage = "Giá phải là một số hợp lệ và lớn hơn hoặc bằng 0.";
    } else {
        $price = floatval($price); // Chuyển đổi thành số thực

        // Xử lý ảnh
        $image = $_FILES['image']['name'] ? $_FILES['image']['name'] : ""; // ảnh mới
        $image2 = $_FILES['image2']['name'] ? $_FILES['image2']['name'] : ""; // ảnh 2
        $image3 = $_FILES['image3']['name'] ? $_FILES['image3']['name'] : ""; // ảnh 3

        // Nếu có product_id thì thực hiện sửa sản phẩm
        if ($product_id) {
            $sql = "UPDATE products SET product_name = '$product_name', price = '$price', 
                    description = '$description', size = '$size', discount = '$discount', 
                    category_id = '$category_id', subcategory_id = '$subcategory_id', 
                    type_id = '$type_id'";

            if ($image) {
                $sql .= ", image = '$image'"; // Cập nhật ảnh mới
            }
            if ($image2) {
                $sql .= ", image2 = '$image2'"; // Cập nhật ảnh 2 mới
            }
            if ($image3) {
                $sql .= ", image3 = '$image3'"; // Cập nhật ảnh 3 mới
            }

            $sql .= " WHERE product_id = $product_id";

            // Di chuyển ảnh mới nếu có
            if ($image) {
                move_uploaded_file($_FILES['image']['tmp_name'], "../assets/img_product/" . $image);
            }
            if ($image2) {
                move_uploaded_file($_FILES['image2']['tmp_name'], "../assets/img_product/" . $image2);
            }
            if ($image3) {
                move_uploaded_file($_FILES['image3']['tmp_name'], "../assets/img_product/" . $image3);
            }
            $successMessage = "Sản phẩm đã được cập nhật thành công!";
        } else {
            // Nếu không có product_id thì thực hiện thêm sản phẩm mới
            $sql = "INSERT INTO products (product_name, price, description, size, discount, category_id, subcategory_id, type_id, image, image2, image3) 
                    VALUES ('$product_name', '$price', '$description', '$size', '$discount', '$category_id', '$subcategory_id', '$type_id', '$image', '$image2', '$image3')";

            // Di chuyển ảnh vào thư mục
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/img_product/" . $image);
            move_uploaded_file($_FILES['image2']['tmp_name'], "../assets/img_product/" . $image2);
            move_uploaded_file($_FILES['image3']['tmp_name'], "../assets/img_product/" . $image3);
            $successMessage = "Sản phẩm đã được thêm thành công!";
        }
        if (Database::query($sql) === false) {
            $errorMessage = mysqli_error(Database::getConnection());
        }
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM products WHERE product_id = $delete_id";
    
    if (Database::query($sql) === false) {
        $errorMessage = mysqli_error(Database::getConnection());
    } else {
        $successMessage = "Sản phẩm đã được xóa thành công!";
        header("Location: admin_edit_product.php"); 
        exit(); 
    }
}
// Xử lý chỉnh sửa sản phẩm
$product = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM products WHERE product_id = $edit_id";
    $edit_result = Database::query($sql);
    $product = mysqli_fetch_assoc($edit_result);
}

// Lấy danh sách sản phẩm để hiển thị
$products = Database::query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Quản Lý Sản Phẩm</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        
        <div class="container mt-4">
            <h1 class="mb-4">Quản Lý Sản Phẩm</h1>
            <!-- Hiển thị thông báo -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php elseif (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <!-- Nút thêm sản phẩm -->
            <button class="btn btn-primary" id="addProductBtn" data-toggle="modal" data-target="#productModal">Thêm Sản Phẩm</button>
            <!-- Modal thêm/sửa sản phẩm -->
            <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formTitle">Thêm Sản Phẩm</h5>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span> 
                                    </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="product_id" id="product_id" value="<?php echo $product['product_id'] ?? ''; ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="product_name">Tên Sản Phẩm</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" required value="<?php echo $product['product_name'] ?? ''; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="price">Giá</label>
                                        <input type="number" class="form-control" id="price" name="price" required value="<?php echo $product['price'] ?? ''; ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="image">Ảnh Sản Phẩm (Tên tệp 1)</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <?php if (isset($product['image'])): ?>
                                            <small>Ảnh hiện tại: <img src="../assets/img_product/<?php echo $product['image']; ?>" width="100"></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="image2">Ảnh Sản Phẩm (Tên tệp 2)</label>
                                        <input type="file" class="form-control" id="image2" name="image2">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="image3">Ảnh Sản Phẩm (Tên tệp 3)</label>
                                        <input type="file" class="form-control" id="image3" name="image3">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="size">Kích Thước</label>
                                        <input type="text" class="form-control" id="size" name="size" value="<?php echo $product['size'] ?? ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô Tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $product['description'] ?? ''; ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="discount">Giảm Giá (%)</label>
                                        <input type="number" class="form-control" id="discount" name="discount" value="<?php echo $product['discount'] ?? 0; ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="category_id">Danh Mục</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php 
                                            $categories = Database::query("SELECT * FROM categories");
                                            while ($category = mysqli_fetch_assoc($categories)): ?>
                                                <option value="<?php echo $category['category_id']; ?>" <?php echo (isset($product) && $product['category_id'] == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="subcategory_id">Phân Loại</label>
                                        <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                            <option value="">Chọn phân loại</option>
                                            <?php 
                                            $subcategories = Database::query("SELECT * FROM subcategories");
                                            while ($subcategory = mysqli_fetch_assoc($subcategories)): ?>
                                                <option value="<?php echo $subcategory['subcategory_id']; ?>" <?php echo (isset($product) && $product['subcategory_id'] == $subcategory['subcategory_id']) ? 'selected' : ''; ?>><?php echo $subcategory['subcategory_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="type_id">Loại Sản Phẩm</label>
                                        <select class="form-control" id="type_id" name="type_id">
                                            <option value="">Chọn loại sản phẩm</option>
                                            <?php 
                                            $productTypes = Database::query("SELECT * FROM product_types");
                                            while ($type = mysqli_fetch_assoc($productTypes)): ?>
                                                <option value="<?php echo $type['type_id']; ?>" <?php echo (isset($product) && $product['type_id'] == $type['type_id']) ? 'selected' : ''; ?>><?php echo $type['type_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hiển thị danh sách sản phẩm -->
            <h2 class="mt-4">Danh Sách Sản Phẩm</h2>
            <div class="row">
                <?php if ($products && mysqli_num_rows($products) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($products)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="../assets/img_product/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                                    <p class="card-text">Giá: <?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></p>
                                    <button class="btn btn-warning editBtn" data-id="<?php echo $product['product_id']; ?>" data-name="<?php echo $product['product_name']; ?>" data-price="<?php echo $product['price']; ?>" data-description="<?php echo $product['description']; ?>" data-size="<?php echo $product['size']; ?>" data-discount="<?php echo $product['discount']; ?>" data-toggle="modal" data-target="#productModal">Chỉnh sửa</button>
                                    <a href="admin_edit_product.php?delete_id=<?php echo $product['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
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
    
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>