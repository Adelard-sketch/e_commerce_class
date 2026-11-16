<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/db_cred.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../classes/CartClass.php';
require_once __DIR__ . '/../core/PaystackClient.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    redirect_to(app_url('views/cart.php'));
}

require_login();

if (!empty($_SESSION['paystack_pending']['reference'])) {
    $_SESSION['error'] = 'You already have a pending Paystack payment. Continue or check that payment before starting another.';
    redirect_to(app_url('views/cart.php'));
}

$submittedToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['csrf_token'] ?? '';
if (!is_string($submittedToken) || !is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $submittedToken)) {
    $_SESSION['error'] = 'Your checkout session expired. Please try again.';
    redirect_to(app_url('views/cart.php'));
}

$email = $_SESSION['customer_email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'A valid email address is required on your account before checkout.';
    redirect_to(app_url('views/cart.php'));
}

$controller = new ProductController();
$cart = new CartClass($controller);
$items = $cart->getItems();
if (empty($items)) {
    $_SESSION['error'] = 'Your cart is empty.';
    redirect_to(app_url('views/cart.php'));
}

$subtotal = 0;
$cartSnapshot = [];
foreach ($items as $item) {
    $subtotal += (float) $item['product_price'] * (int) $item['quantity'];
    $cartSnapshot[(int) $item['product_id']] = (int) $item['quantity'];
}
ksort($cartSnapshot, SORT_NUMERIC);
$sessionCart = $_SESSION['cart'] ?? [];
$normalizedSessionCart = [];
if (is_array($sessionCart)) {
    foreach ($sessionCart as $productId => $quantity) {
        $productId = filter_var($productId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $quantity = filter_var($quantity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($productId !== false && $quantity !== false) {
            $normalizedSessionCart[$productId] = $quantity;
        }
    }
}
ksort($normalizedSessionCart, SORT_NUMERIC);
if ($normalizedSessionCart !== $cartSnapshot) {
    $_SESSION['error'] = 'Your cart contains unavailable products. Please review it before checkout.';
    redirect_to(app_url('views/cart.php'));
}

$amountPesewas = (int) round($subtotal * 100);

if ($amountPesewas < 1) {
    $_SESSION['error'] = 'The cart total must be greater than zero.';
    redirect_to(app_url('views/cart.php'));
}

$paymentConfig = paystack_config();
$callbackUrl = $paymentConfig['callback_url'] ?? '';
if (!is_string($callbackUrl) || trim($callbackUrl) === '') {
    $callbackUrl = getenv('PAYSTACK_CALLBACK_URL');
}
if ((!is_string($callbackUrl) || trim($callbackUrl) === '') && defined('PAYSTACK_CALLBACK_URL')) {
    $callbackUrl = PAYSTACK_CALLBACK_URL;
}
$callbackHost = is_string($callbackUrl) ? parse_url($callbackUrl, PHP_URL_HOST) : false;
$callbackScheme = is_string($callbackUrl) ? parse_url($callbackUrl, PHP_URL_SCHEME) : false;
if (!is_string($callbackUrl)
    || filter_var($callbackUrl, FILTER_VALIDATE_URL) === false
    || !in_array($callbackScheme, ['http', 'https'], true)
    || $callbackHost === false
    || parse_url($callbackUrl, PHP_URL_USER) !== null
    || parse_url($callbackUrl, PHP_URL_PASS) !== null) {
    $_SESSION['error'] = 'Paystack callback is not configured. Set PAYSTACK_CALLBACK_URL to this site\'s full callback address.';
    redirect_to(app_url('views/cart.php'));
}

try {
    $paystack = new PaystackClient();
    $payment = $paystack->initializeTransaction(
        $email,
        $amountPesewas,
        $callbackUrl,
        [
            'customer_id' => (int) $_SESSION['customer_id'],
            'cart' => $cartSnapshot,
        ]
    );

    $authorizationUrl = $payment['authorization_url'] ?? '';
    $authorizationHost = is_string($authorizationUrl) ? parse_url($authorizationUrl, PHP_URL_HOST) : false;
    if (empty($authorizationUrl)
        || empty($payment['reference'])
        || !is_string($authorizationUrl)
        || parse_url($authorizationUrl, PHP_URL_SCHEME) !== 'https'
        || $authorizationHost !== 'checkout.paystack.com') {
        throw new RuntimeException('Paystack did not provide a checkout link.');
    }

    $_SESSION['paystack_pending'] = [
        'reference' => $payment['reference'],
        'authorization_url' => $authorizationUrl,
        'customer_id' => (int) $_SESSION['customer_id'],
        'email' => strtolower($email),
        'amount' => $amountPesewas,
        'cart' => $cartSnapshot,
    ];

    redirect_to($authorizationUrl);
} catch (RuntimeException $exception) {
    $_SESSION['error'] = 'Payment could not be started: ' . $exception->getMessage();
    redirect_to(app_url('views/cart.php'));
}
