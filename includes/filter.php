<style>
    .filter-form {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .filter-form h5, .filter-form h6 {
        color: #333;
    }

    .filter-form .custom-control {
        margin-bottom: 10px;
    }

    .filter-form .custom-control-label {
        font-size: 14px;
        color: #555;
    }

    .filter-form .btn {
        font-size: 16px;
        padding: 10px;
        border-radius: 25px;
        text-transform: uppercase;
        transition: background-color 0.3s, transform 0.2s; 
    }

    .filter-form .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .filter-form .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #007bff;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .filter-form {
            padding: 15px; 
        }

        .filter-form .btn {
            font-size: 14px; 
        }
    }
</style>

<form method="GET" action="shop.php" class="filter-form border-bottom mb-4 pb-4">
    <h5 class="mb-4">Lọc Sản Phẩm</h5>

    <!-- Bộ lọc giá -->
    <h6 class="mb-3">Giá</h6>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="price1" name="price[]" value="1" <?= in_array("1", $price_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="price1">100.000 - 500.000 VNĐ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="price2" name="price[]" value="2" <?= in_array("2", $price_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="price2">500.000 - 1.000.000 VNĐ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="price3" name="price[]" value="3" <?= in_array("3", $price_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="price3">1.000.000 - 2.000.000 VNĐ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="price4" name="price[]" value="4" <?= in_array("4", $price_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="price4">Trên 2.000.000 VNĐ</label>
    </div>

    <!-- Bộ lọc subcategories sản phẩm -->
    <h6 class="mt-4 mb-3">Loại</h6>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type1" name="type[]" value="Áo sơ mi nam" <?= in_array("Áo sơ mi nam", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type1">Áo sơ mi nam</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type2" name="type[]" value="Áo thun nam" <?= in_array("Áo thun nam", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type2">Áo thun nam</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type3" name="type[]" value="Quần jeans nam" <?= in_array("Quần jeans nam", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type3">Quần jeans nam</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type4" name="type[]" value="Váy đầm nữ" <?= in_array("Váy đầm nữ", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type4">Váy đầm nữ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type5" name="type[]" value="Áo thun nữ" <?= in_array("Áo thun nữ", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type5">Áo thun nữ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type6" name="type[]" value="Áo khoác nữ" <?= in_array("Áo khoác nữ", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type6">Áo khoác nữ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type7" name="type[]" value="Giày cao gót" <?= in_array("Giày cao gót", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type7">Giày cao gót</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type8" name="type[]" value="Dép sandal" <?= in_array("Dép sandal", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type8">Dép sandal</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type9" name="type[]" value="Mũ thời trang" <?= in_array("Mũ thời trang", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type9">Mũ thời trang</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type10" name="type[]" value="Kính mát" <?= in_array("Kính mát", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type10">Kính mát</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type11" name="type[]" value="Ba lô trẻ em" <?= in_array("Ba lô trẻ em", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type11">Ba lô trẻ em</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type12" name="type[]" value="Quần áo bé trai" <?= in_array("Quần áo bé trai", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type12">Quần áo bé trai</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type13" name="type[]" value="Quần áo bé gái" <?= in_array("Quần áo bé gái", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type13">Quần áo bé gái</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type14" name="type[]" value="Thời trang công sở" <?= in_array("Thời trang công sở", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type14">Thời trang công sở</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type15" name="type[]" value="Thời trang dạo phố" <?= in_array("Thời trang dạo phố", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type15">Thời trang dạo phố</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type16" name="type[]" value="Phụ kiện độc quyền" <?= in_array("Phụ kiện độc quyền", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type16">Phụ kiện độc quyền</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type17" name="type[]" value="Túi xách cao cấp" <?= in_array("Túi xách cao cấp", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type17">Túi xách cao cấp</label>
    </div>

    <!-- Bộ lọc kích thước -->
    <h6 class="mt-4 mb-3">Kích Thước</h6>
    <?php 
    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'ONE SIZE'];
    foreach ($sizes as $size): ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="size<?= $size ?>" name="size[]" value="<?= $size ?>" <?= in_array($size, $size_filter) ? 'checked' : '' ?>>
            <label class="custom-control-label" for="size<?= $size ?>"><?= $size ?></label>
        </div>
    <?php endforeach; ?>

    <!-- Nút Lọc -->
    <button type="submit" class="btn btn-primary btn-block mt-4">Lọc</button>
</form>