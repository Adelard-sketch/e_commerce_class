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
$csrfToken = $_SESSION['csrf_token'] ?? '';
if ($csrfToken === '') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $csrfToken = $_SESSION['csrf_token'];
}
$pendingPayment = $_SESSION['paystack_pending'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart | Shoppin</title>
    <link rel="stylesheet" href="<?= app_url('css/style.css') ?>">
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
            <p class="cart-continue"><a class="btn-details" href="<?= app_url('index.php') ?>">Continue shopping</a></p>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <article class="cart-item">
                            <div class="cart-item__image">
                                <?php if (!empty($item['product_image'])): ?>
                                    <img src="<?= app_url('images/products/' . rawurlencode($item['product_image'])) ?>"
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
                                    <form method="POST" action="<?= app_url('actions/cart_action.php') ?>" class="cart-quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                        <label for="quantity-<?= (int) $item['product_id'] ?>">Qty</label>
                                        <input id="quantity-<?= (int) $item['product_id'] ?>"
                                               type="number" name="quantity" min="1" step="1"
                                               value="<?= (int) $item['quantity'] ?>" required>
                                        <button type="submit" class="btn-details">Update</button>
                                    </form>
                                    <form method="POST" action="<?= app_url('actions/cart_action.php') ?>">
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
                    <?php if (is_logged_in()): ?>
                        <?php if (is_array($pendingPayment) && !empty($pendingPayment['authorization_url'])): ?>
                            <a class="cart-payment-button" href="<?= htmlspecialchars($pendingPayment['authorization_url']) ?>">Continue Paystack payment</a>
                            <p class="cart-payment-notice" role="status">A Paystack payment is already in progress. You can continue it or check its status below.</p>
                        <?php else: ?>
                            <form method="POST" action="<?= app_url('actions/paystack_checkout_action.php') ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                <button class="cart-payment-button" type="submit">Pay with Paystack</button>
                            </form>
                            <p class="cart-payment-notice" role="status">Secure checkout in Ghana cedis (GHS). Test payments use your Paystack test keys.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="cart-payment-button cart-payment-login" href="<?= app_url('views/login.php') ?>">Log in to checkout</a>
                        <p class="cart-payment-notice" role="status">Please log in before paying with Paystack.</p>
                    <?php endif; ?>
                    <?php if (is_logged_in() && is_array($pendingPayment) && !empty($pendingPayment['reference'])): ?>
                        <form method="GET" action="<?= app_url('actions/paystack_callback_action.php') ?>">
                            <input type="hidden" name="reference" value="<?= htmlspecialchars($pendingPayment['reference']) ?>">
                            <button class="cart-payment-button cart-payment-check" type="submit">Check pending payment</button>
                        </form>
                    <?php endif; ?>
                    <a class="cart-summary__continue" href="<?= app_url('index.php') ?>">Continue shopping</a>
                </aside>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

</body>
</html>
