<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

// Load the selected products.
$pageTitle = 'Featured Products';

if (isset($_GET['cat']) && filter_var($_GET['cat'], FILTER_VALIDATE_INT)) {
    $products  = $controller->getProductsByCategory((int) $_GET['cat']);
    $cat       = $controller->getCategoryById((int) $_GET['cat']);
    $pageTitle = $cat ? htmlspecialchars($cat['cat_name']) : 'Category';

} elseif (isset($_GET['brand']) && filter_var($_GET['brand'], FILTER_VALIDATE_INT)) {
    $products  = $controller->getProductsByBrand((int) $_GET['brand']);
    $brand     = $controller->getBrandById((int) $_GET['brand']);
    $pageTitle = $brand ? htmlspecialchars($brand['brand_name']) : 'Brand';

} else {
    $products = $controller->getFeaturedProducts(6);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shoppin — <?= $pageTitle ?></title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body shop-body">

<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="shop-layout">

    <!-- Product filters -->
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <!-- Product content -->
    <main class="shop-main">

        <!-- Product listing -->
        <h1 class="shop-heading"><?= $pageTitle ?></h1>

        <?php if (empty($products)): ?>
            <p class="empty-note">No products found.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <article class="product-card">
                        <a href="/ecomlab/views/single_product.php?pro_id=<?= (int)$p['product_id'] ?>">
                            <div class="product-card__img-wrap">
                                <?php if (!empty($p['product_image'])): ?>
                                    <img src="/ecomlab/images/products/<?= htmlspecialchars($p['product_image']) ?>"
                                         alt="<?= htmlspecialchars($p['product_title']) ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="product-card__no-img">No Image</div>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="product-card__body">
                            <h2 class="product-card__title">
                                <a href="/ecomlab/views/single_product.php?pro_id=<?= (int)$p['product_id'] ?>">
                                    <?= htmlspecialchars($p['product_title']) ?>
                                </a>
                            </h2>
                            <p class="product-card__meta">
                                <?= htmlspecialchars($p['cat_name']) ?> &middot;
                                <?= htmlspecialchars($p['brand_name']) ?>
                            </p>
                            <p class="product-card__price">GHS <?= number_format((float)$p['product_price'], 2) ?></p>
                            <div class="product-card__actions">
                                <a class="btn-details"
                                   href="/ecomlab/views/single_product.php?pro_id=<?= (int)$p['product_id'] ?>">
                                    Details
                                </a>
                                <a class="btn-cart"
                                   href="/ecomlab/index.php?add_cart=<?= (int)$p['product_id'] ?>">
                                    Add to Cart
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
