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

    <!-- Bộ lọc loại sản phẩm -->
    <h6 class="mt-4 mb-3">Loại</h6>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type1" name="type[]" value="Túi" <?= in_array("Túi", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type1">Túi</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type2" name="type[]" value="Nón" <?= in_array("Nón", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type2">Nón</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type3" name="type[]" value="Blazer" <?= in_array("Blazer", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type3">Blazer</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type4" name="type[]" value="Mũ" <?= in_array("Mũ", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type4">Mũ</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type5" name="type[]" value="Quần cargo" <?= in_array("Quần cargo", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type5">Quần cargo</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type6" name="type[]" value="Áo gile" <?= in_array("Áo gile", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type6">Áo gile</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type7" name="type[]" value="Hoodie" <?= in_array("Hoodie", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type7">Hoodie</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type8" name="type[]" value="Áo khoác" <?= in_array("Áo khoác", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type8">Áo khoác</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type9" name="type[]" value="Jeans" <?= in_array("Jeans", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type9">Jeans</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type10" name="type[]" value="Joggers" <?= in_array("Joggers", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type10">Joggers</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type11" name="type[]" value="Knitwear" <?= in_array("Knitwear", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type11">Knitwear</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="type12" name="type[]" value="Áo polo" <?= in_array("Áo polo", $type_filter) ? 'checked' : '' ?>>
        <label class="custom-control-label" for="type12">Áo polo</label>
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