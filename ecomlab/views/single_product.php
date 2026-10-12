<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT);

if (!$pro_id || $pro_id < 1) {
    redirect_to('/ecomlab/index.php');
}

$controller = new ProductController();
$product    = $controller->getProductById($pro_id);

if (!$product) {
    redirect_to('/ecomlab/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($product['product_title']) ?> | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body shop-body">

<!-- Shared navigation -->
<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="shop-layout">

    <!-- Product filters -->
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <main class="shop-main">

        <!-- Product details -->
        <a class="back-link" href="/ecomlab/index.php">&larr; Back to shop</a>

        <div class="single-product">

            <!-- Product image -->
            <div class="single-product__img-wrap">
                <?php if (!empty($product['product_image'])): ?>
                    <img src="/ecomlab/images/products/<?= htmlspecialchars($product['product_image']) ?>"
                         alt="<?= htmlspecialchars($product['product_title']) ?>">
                <?php else: ?>
                    <div class="product-card__no-img large">No Image Available</div>
                <?php endif; ?>
            </div>

            <!-- Product information -->
            <div class="single-product__details">

                <h1><?= htmlspecialchars($product['product_title']) ?></h1>

                <p class="single-product__meta">
                    <span>Category:</span>
                    <a href="/ecomlab/index.php?cat=<?= (int)$product['product_cat'] ?>">
                        <?= htmlspecialchars($product['cat_name']) ?>
                    </a>
                </p>
                <p class="single-product__meta">
                    <span>Brand:</span>
                    <a href="/ecomlab/index.php?brand=<?= (int)$product['product_brand'] ?>">
                        <?= htmlspecialchars($product['brand_name']) ?>
                    </a>
                </p>

                <p class="single-product__price">GHS <?= number_format((float)$product['product_price'], 2) ?></p>

                <?php if (!empty($product['product_desc'])): ?>
                    <p class="single-product__desc"><?= nl2br(htmlspecialchars($product['product_desc'])) ?></p>
                <?php endif; ?>

                <?php if (!empty($product['product_keywords'])): ?>
                    <p class="single-product__keywords">
                        <strong>Tags:</strong>
                        <?php foreach (explode(',', $product['product_keywords']) as $kw): ?>
                            <span class="tag"><?= htmlspecialchars(trim($kw)) ?></span>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>

                <div class="single-product__actions">
                    <a class="btn-cart btn-cart--large"
                       href="/ecomlab/index.php?add_cart=<?= (int)$product['product_id'] ?>">
                        Add to Cart
                    </a>
                </div>

                <?php if (is_admin()): ?>
                    <div class="admin-product-link">
                        <a href="/ecomlab/views/admin/product.php?edit_id=<?= (int)$product['product_id'] ?>">
                            &#9998; Edit this product
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
