<?php
include('../config/database.php');
session_start();
$searchResults = [];
$query = ''; 
if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    // Truy vấn trực tiếp với LIKE
    $searchQuery = "SELECT * FROM products WHERE product_name LIKE '%$query%'";
    $result = Database::query($searchQuery);
    if ($result) {
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    }
}
// add vào yêu thích
if (isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
        echo "<script type='text/javascript'>
                alert('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích.');
        </script>";
    } else {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user']['user_id'];
        Database::addToFavorites($user_id, $product_id);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tìm Kiếm - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo/HPL-logo.png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <?php if (count($searchResults) == 0): ?>
            <form action="search.php" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="<?= $query; ?>" required>
                    <button type="submit" class="input-group-text bg-transparent text-primary" style="border: none; background: none; cursor: pointer;">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        <?php endif; ?>
        <!-- sản phẩm -->
        <?php if ($query !== ''): ?>
            <h2>KẾT QUẢ CHO: <strong><?= $query; ?></strong></h2>
            <div class="row">
                <?php if (count($searchResults) > 0): ?>
                    <?php foreach ($searchResults as $product): ?>
                        <?php include 'product_item.php'; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không tìm thấy sản phẩm nào phù hợp.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
