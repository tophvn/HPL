<?php
include('../config/Database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BỘ SƯU TẬP - HPL FASHION</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href=" " rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
    .cat-img img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
</style>

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <?php
            $q1 = Database::query("SELECT categories.*, COUNT(products.product_id) AS product_count
                        FROM categories
                        LEFT JOIN products ON categories.category_id = products.category_id
                        GROUP BY categories.category_id"
            );

            while ($r1 = $q1->fetch_array()) {
            ?>
                <div class="col-lg-4 col-md-6 pb-1">
                    <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                        <p class="text-right"><?php echo $r1['product_count']; ?> Sản Phẩm</p>
                        <a href="shop.php?category=<?php echo $r1['category_id']; ?>" class="cat-img position-relative overflow-hidden mb-3">
                            <img class="img-fluid" src="../img/img-collection/<?php echo $r1['category_image']; ?>" alt="">
                        </a>
                        <h5 class="font-weight-semi-bold m-0"><?php echo $r1['category_name']; ?></h5>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>