<?php
require_once __DIR__ . '/../../core/core.php';
?>
<header class="shop-header">
    <!-- Store logo -->
    <div class="shop-header__brand">
        <a class="shop-logo" href="/ecomlab/index.php">
            <img src="/ecomlab/images/shoppnLogo.png" alt="">
            <span>Shoppin</span>
        </a>
    </div>

    <!-- Main navigation -->
    <nav class="auth-nav shop-nav" aria-label="Main navigation">
        <a href="/ecomlab/index.php">Home</a>

        <?php if (is_logged_in()): ?>

            <?php if ($showAccountLink ?? true): ?>
                <a href="/ecomlab/views/account/my_account.php">My Account</a>
            <?php endif; ?>

            <?php if (is_admin()): ?>
                <span class="nav-divider">|</span>
                <strong class="nav-admin-label">Admin:</strong>
                <a href="/ecomlab/views/admin/brand.php">Brands</a>
                <a href="/ecomlab/views/admin/category.php">Categories</a>
                <a href="/ecomlab/views/admin/product.php">Add Product</a>
            <?php endif; ?>

            <a href="/ecomlab/index.php?logout=1" class="nav-logout">Logout</a>

        <?php else: ?>
            <a href="/ecomlab/views/register.php">Register</a>
            <a href="/ecomlab/views/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
