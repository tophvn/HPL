<?php
include('../config/database.php');
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['roles'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$product_id = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $product_name = $_POST['product_name'];
    $price = floatval($_POST['price']);
    $description = $_POST['description'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $discount = $_POST['discount'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $brand_id = $_POST['brand_id'];
    $image = $_FILES['image']['name'] ?? "";
    $image2 = $_FILES['image2']['name'] ?? "";
    $image3 = $_FILES['image3']['name'] ?? "";

    if ($price < 0) {
        $errorMessage = "Giá phải là một số hợp lệ và lớn hơn hoặc bằng 0.";
    } else {
        // Cập nhật sản phẩm
        if ($product_id) {
            $sql = "UPDATE products SET product_name = '$product_name', price = '$price', description = '$description', 
            size = '$size', color = '$color', discount = '$discount', category_id = '$category_id', subcategory_id = '$subcategory_id', 
            brand_id = '$brand_id'";
            if ($image) $sql .= ", image = '$image'";
            if ($image2) $sql .= ", image2 = '$image2'";
            if ($image3) $sql .= ", image3 = '$image3'";
            $sql .= " WHERE product_id = $product_id";
        } else {
            // Thêm sản phẩm mới
            $sql = "INSERT INTO products (product_name, price, description, size, color, discount, category_id, subcategory_id, brand_id, image, image2, image3) 
            VALUES ('$product_name', '$price', '$description', '$size', '$color', '$discount', '$category_id', '$subcategory_id', '$brand_id', '$image', '$image2', '$image3')";
        }

        if (Database::query($sql) === false) {
            $errorMessage = "Lỗi: Không thể thực hiện truy vấn.";
        } else {
            $successMessage = $product_id ? "Sản phẩm đã được cập nhật thành công!" : "Sản phẩm đã được thêm thành công!";
        }
        // Xử lý file upload
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/img_product/$image");
        move_uploaded_file($_FILES['image2']['tmp_name'], "../assets/img_product/$image2");
        move_uploaded_file($_FILES['image3']['tmp_name'], "../assets/img_product/$image3");
    }
}

// Xóa sản phẩm
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM products WHERE product_id = $delete_id";
    if (Database::query($sql) === false) {
        $errorMessage = "Lỗi: Không thể thực hiện truy vấn.";
    } else {
        $successMessage = "Sản phẩm đã được xóa thành công!";
        header("Location: admin_edit_product.php");
        exit();
    }
}
$products = Database::query("SELECT * FROM products ORDER BY created_at DESC");
$brands = Database::query("SELECT * FROM brands");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/auth.css" rel="stylesheet">
    <title>Quản Lý Sản Phẩm</title>
    <style>
        
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        
        <div class="container">
            <div class="table-container">
                <h1 class="mb-4">Danh Sách Sản Phẩm</h1>
                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success"><?php echo $successMessage; ?></div>
                <?php elseif (isset($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>

                <div class="scrollable-table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($products && mysqli_num_rows($products) > 0): ?>
                                <?php while ($product = mysqli_fetch_assoc($products)): ?>
                                    <tr>
                                        <td><img src="../assets/img_product/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image"></td>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo number_format($product['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-custom editBtn" data-id="<?php echo $product['product_id']; ?>" data-name="<?php echo $product['product_name']; ?>" data-price="<?php echo $product['price']; ?>" data-description="<?php echo $product['description']; ?>" data-size="<?php echo $product['size']; ?>" data-color="<?php echo $product['color']; ?>" data-discount="<?php echo $product['discount']; ?>" data-category="<?php echo $product['category_id']; ?>" data-subcategory="<?php echo $product['subcategory_id']; ?>" data-brand="<?php echo $product['brand_id']; ?>">Chỉnh sửa</button>
                                            <a href="admin_edit_product.php?delete_id=<?php echo $product['product_id']; ?>" class="btn btn-danger btn-custom" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có sản phẩm nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data" id="productForm">
                    <input type="hidden" name="product_id" id="product_id" value="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="product_name">Tên Sản Phẩm</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="price">Giá</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="discount">Giảm Giá (%)</label>
                            <input type="number" class="form-control" id="discount" name="discount" value="0">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="size">Kích Thước</label>
                            <input type="text" class="form-control" id="size" name="size">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="color">Màu Sắc</label>
                            <input type="text" class="form-control" id="color" name="color">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="category_id">Danh Mục</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php 
                                $categories = Database::query("SELECT * FROM categories");
                                while ($category = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
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
                                    <option value="<?php echo $subcategory['subcategory_id']; ?>"><?php echo $subcategory['subcategory_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="brand_id">Thương Hiệu</label>
                            <select class="form-control" id="brand_id" name="brand_id" required>
                                <option value="">Chọn thương hiệu</option>
                                <?php 
                                while ($brand = mysqli_fetch_assoc($brands)): ?>
                                    <option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                            
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="description">Mô Tả</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="image">Ảnh Sản Phẩm (Tên tệp 1)</label>
                            <input type="file" class="form-control" id="image" name="image">
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
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom">Lưu</button>
                    <button type="button" class="btn btn-secondary btn-custom" id="clearFormBtn">Xóa</button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('.editBtn').click(function() {
                $('#product_id').val($(this).data('id'));
                $('#product_name').val($(this).data('name'));
                $('#price').val($(this).data('price'));
                $('#description').val($(this).data('description'));
                $('#size').val($(this).data('size'));
                $('#color').val($(this).data('color'));
                $('#discount').val($(this).data('discount'));
                $('#category_id').val($(this).data('category'));
                $('#subcategory_id').val($(this).data('subcategory'));
                $('#brand_id').val($(this).data('brand'));
            });

            $('#clearFormBtn').click(function() {
                $('#productForm')[0].reset();
                $('#product_id').val('');
            });
        });
    </script>
</body>
</html>