<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$query      = trim(strip_tags($_GET['user_query'] ?? ''));
$controller = new ProductController();
$products   = $query !== '' ? $controller->searchProducts($query) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search: <?= htmlspecialchars($query) ?> | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body shop-body">

<!-- Shared navigation -->
<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="shop-layout">

    <!-- Product filters -->
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <main class="shop-main">

        <!-- Search results -->
        <h1 class="shop-heading">
            <?php if ($query !== ''): ?>
                Search results for: <em><?= htmlspecialchars($query) ?></em>
            <?php else: ?>
                Please enter a search term
            <?php endif; ?>
        </h1>

        <?php if ($query === ''): ?>
            <p class="empty-note">Use the search box to find products.</p>

        <?php elseif (empty($products)): ?>
            <p class="empty-note">No products found for "<?= htmlspecialchars($query) ?>".</p>

        <?php else: ?>
            <p class="result-count"><?= count($products) ?> result<?= count($products) !== 1 ? 's' : '' ?> found</p>
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
