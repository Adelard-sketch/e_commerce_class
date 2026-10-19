<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../classes/CartClass.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    redirect_to('/ecomlab/views/cart.php');
}

$action = $_POST['action'] ?? '';
$productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$controller = new ProductController();
$cart = new CartClass($controller);

if ($action === 'add') {
    if ($productId === false || $quantity === false) {
        $_SESSION['error'] = 'Please choose a valid product quantity.';
    } elseif (!$cart->addProduct($productId, $quantity)) {
        $_SESSION['error'] = 'That product is no longer available.';
    } else {
        $_SESSION['success'] = 'Product added to your cart.';
    }
} elseif ($action === 'update') {
    if ($productId === false || $quantity === false || !$cart->updateQuantity($productId, $quantity)) {
        $_SESSION['error'] = 'Unable to update that cart item.';
    } else {
        $_SESSION['success'] = 'Cart quantity updated.';
    }
} elseif ($action === 'remove') {
    if ($productId === false || !$cart->removeProduct($productId)) {
        $_SESSION['error'] = 'Unable to remove that cart item.';
    } else {
        $_SESSION['success'] = 'Product removed from your cart.';
    }
} else {
    $_SESSION['error'] = 'Invalid cart action.';
}

redirect_to('/ecomlab/views/cart.php');
