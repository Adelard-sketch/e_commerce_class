<?php
// sidebar.php — requires ProductController to be loaded by the including page
// It uses $controller if already set, otherwise creates its own instance.
if (!isset($controller)) {
    require_once __DIR__ . '/../../controllers/ProductController.php';
    $controller = new ProductController();
}

$sidebarCategories = $controller->getAllCategories();
$sidebarBrands     = $controller->getAllBrands();

// Search query (preserve in sidebar input)
$searchQuery = htmlspecialchars(trim($_GET['user_query'] ?? ''));
?>
<aside class="sidebar">

    <!-- Search -->
    <div class="sidebar-widget">
        <h3 class="sidebar-widget__title">Search</h3>
        <form class="sidebar-search" method="GET" action="/ecomlab/views/search_results.php">
            <input type="search" name="user_query" placeholder="Search products…"
                   value="<?= $searchQuery ?>" aria-label="Search products">
            <button type="submit" aria-label="Search">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <circle cx="10.8" cy="10.8" r="6.8"></circle>
                    <path d="m16 16 5 5"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Categories -->
    <div class="sidebar-widget">
        <h3 class="sidebar-widget__title">Categories</h3>
        <?php if (empty($sidebarCategories)): ?>
            <p class="sidebar-empty">No categories yet.</p>
        <?php else: ?>
            <ul class="sidebar-list">
                <li><a href="/ecomlab/index.php">All Products</a></li>
                <?php foreach ($sidebarCategories as $cat): ?>
                    <li>
                        <a href="/ecomlab/index.php?cat=<?= (int)$cat['cat_id'] ?>"
                           <?= (isset($_GET['cat']) && (int)$_GET['cat'] === (int)$cat['cat_id']) ? 'class="active"' : '' ?>>
                            <?= htmlspecialchars($cat['cat_name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Brands -->
    <div class="sidebar-widget">
        <h3 class="sidebar-widget__title">Brands</h3>
        <?php if (empty($sidebarBrands)): ?>
            <p class="sidebar-empty">No brands yet.</p>
        <?php else: ?>
            <ul class="sidebar-list">
                <?php foreach ($sidebarBrands as $brand): ?>
                    <li>
                        <a href="/ecomlab/index.php?brand=<?= (int)$brand['brand_id'] ?>"
                           <?= (isset($_GET['brand']) && (int)$_GET['brand'] === (int)$brand['brand_id']) ? 'class="active"' : '' ?>>
                            <?= htmlspecialchars($brand['brand_name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</aside>
