<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card product-item border-0">
        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
            <?php $image = '../assets/img_product/' . $product['image']; ?>
            <a href="detail.php?id=<?= $product['product_id'] ?>">
                <?php $image = '../assets/img_product/' . $product['image']; ?>
                <img class="img-fluid w-100 fixed-img" src="<?= $image ?>" alt="">
            </a>
            <?php if ($product['discount'] >0): ?>
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
                $discounted_price = $product['price'] * (1 - $discount/100);
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
