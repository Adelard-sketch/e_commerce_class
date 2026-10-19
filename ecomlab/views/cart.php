<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../classes/CartClass.php';

$controller = new ProductController();
$cart = new CartClass($controller);
$items = $cart->getItems();
$subtotal = array_sum(array_column($items, 'line_total'));
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body shop-body">

<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="shop-layout">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <main class="shop-main">
        <h1 class="shop-heading">Shopping Cart</h1>

        <?php if ($success): ?>
            <div class="flash flash--success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="flash flash--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <p class="empty-note">Your cart is empty.</p>
            <p class="cart-continue"><a class="btn-details" href="/ecomlab/index.php">Continue shopping</a></p>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <article class="cart-item">
                            <div class="cart-item__image">
                                <?php if (!empty($item['product_image'])): ?>
                                    <img src="/ecomlab/images/products/<?= htmlspecialchars($item['product_image']) ?>"
                                         alt="<?= htmlspecialchars($item['product_title']) ?>">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </div>

                            <div class="cart-item__details">
                                <h2><?= htmlspecialchars($item['product_title']) ?></h2>
                                <p class="cart-item__meta"><?= htmlspecialchars($item['brand_name']) ?></p>
                                <p class="cart-item__price">GHS <?= number_format((float) $item['product_price'], 2) ?> each</p>

                                <div class="cart-item__actions">
                                    <form method="POST" action="/ecomlab/actions/cart_action.php" class="cart-quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                        <label for="quantity-<?= (int) $item['product_id'] ?>">Qty</label>
                                        <input id="quantity-<?= (int) $item['product_id'] ?>"
                                               type="number" name="quantity" min="1" step="1"
                                               value="<?= (int) $item['quantity'] ?>" required>
                                        <button type="submit" class="btn-details">Update</button>
                                    </form>
                                    <form method="POST" action="/ecomlab/actions/cart_action.php">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                        <button type="submit" class="cart-remove">Remove</button>
                                    </form>
                                </div>
                            </div>

                            <p class="cart-item__total">
                                GHS <?= number_format((float) $item['line_total'], 2) ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>

                <aside class="cart-summary">
                    <h2>Order Summary</h2>
                    <p><span>Subtotal</span><strong>GHS <?= number_format((float) $subtotal, 2) ?></strong></p>
                    <p class="cart-summary__note">Shipping and taxes are not included in this subtotal.</p>
                    <button class="cart-payment-button" type="button" disabled>Proceed to Payment</button>
                    <p class="cart-payment-notice" role="status">Payment will be implemented soon.</p>
                    <a class="cart-summary__continue" href="/ecomlab/index.php">Continue shopping</a>
                </aside>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
