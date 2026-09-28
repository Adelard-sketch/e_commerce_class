<?php
require_once __DIR__ . '/../../core/core.php';
?>
<nav class="auth-nav home-nav" aria-label="Main navigation">
    <?php if (is_logged_in()): ?>
        <a href="/ecomlab/index.php">Home</a>
        <?php if ($showAccountLink ?? true): ?>
            <a href="/ecomlab/views/account/my_account.php">My Account</a>
        <?php endif; ?>
        <a href="/ecomlab/index.php?logout=1">Logout</a>
    <?php else: ?>
        <a href="/ecomlab/views/register.php">Register</a>
        <a href="/ecomlab/views/login.php">Login</a>
    <?php endif; ?>
</nav>
