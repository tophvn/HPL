<?php
include_once('../config/database.php');

$price_filter = $_GET['price'] ?? []; 
$type_filter = $_GET['type'] ?? [];
$size_filter = $_GET['size'] ?? [];

$types = Database::query("SELECT * FROM subcategories");
$sizes = ['S', 'M', 'L', 'XL', 'XXL', 'ONE SIZE']; 
$price_ranges=[
    1 => "Giá từ 100,000 đến 500,000",
    2 => "Giá từ 500,001 đến 1,000,000",
    3 => "Giá từ 1,000,001 đến 2,000,000",
    4 => "Giá trên 2,000,000"
];
?>


<form method="GET" action="shop.php" class="filter-form border-bottom mb-4 pb-4">
    <h5 class="mb-4">Lọc Sản Phẩm</h5>
    <!-- Bộ lọc giá -->
    <h6 class="mb-3">Giá</h6>
    <?php foreach ($price_ranges as $id => $label): ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="price<?= $id ?>" name="price[]" value="<?= $id ?>" <?= in_array($id, $price_filter) ? 'checked' : '' ?>>
            <label class="custom-control-label" for="price<?= $id ?>"><?= $label ?></label>
        </div>
    <?php endforeach; ?>

    <!-- Bộ lọc loại sản phẩm -->
    <h6 class="mt-4 mb-3">Loại</h6>
    <?php while ($type = $types->fetch_assoc()): ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="type<?= $type['subcategory_id'] ?>" name="type[]" value="<?= $type['subcategory_id'] ?>" <?= in_array($type['subcategory_id'], $type_filter) ? 'checked' : '' ?>>
            <label class="custom-control-label" for="type<?= $type['subcategory_id'] ?>"><?= $type['subcategory_name'] ?></label>
        </div>
    <?php endwhile; ?>

    <!-- Bộ lọc kích thước -->
    <h6 class="mt-4 mb-3">Kích Thước</h6>
    <?php foreach ($sizes as $size): ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="size<?= $size ?>" name="size[]" value="<?= $size ?>" <?= in_array($size, $size_filter) ? 'checked' : '' ?>>
            <label class="custom-control-label" for="size<?= $size ?>"><?= $size ?></label>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary btn-block mt-4">Lọc</button>
</form>