<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/PaystackClient.php';

if (!is_logged_in()) {
    $_SESSION['error'] = 'Please log in again to confirm your payment.';
    redirect_to(app_url('views/login.php'));
}

$pending = $_SESSION['paystack_pending'] ?? null;
$reference = $_GET['reference'] ?? '';
if (is_string($reference)) {
    $reference = trim($reference);
}
if (!is_array($pending)
    || !is_string($reference)
    || $reference === ''
    || !isset($pending['reference'], $pending['customer_id'], $pending['email'], $pending['amount'], $pending['cart'])
    || !hash_equals((string) $pending['reference'], $reference)
    || (int) $pending['customer_id'] !== (int) $_SESSION['customer_id']) {
    $_SESSION['error'] = 'There is no matching pending payment to confirm.';
    redirect_to(app_url('views/cart.php'));
}

try {
    $paystack = new PaystackClient();
    $transaction = $paystack->verifyTransaction($reference);
} catch (RuntimeException $exception) {
    $_SESSION['error'] = 'Payment confirmation failed: ' . $exception->getMessage();
    redirect_to(app_url('views/cart.php'));
}

$transactionCustomer = $transaction['customer'] ?? [];
$transactionEmail = is_array($transactionCustomer)
    ? strtolower((string) ($transactionCustomer['email'] ?? ''))
    : '';
$transactionStatus = $transaction['status'] ?? '';
if ($transactionStatus !== 'success') {
    if ($transactionStatus === 'pending') {
        $_SESSION['error'] = 'Your payment is still processing. Check its status again in a moment.';
    } else {
        unset($_SESSION['paystack_pending']);
        $_SESSION['error'] = 'Paystack did not confirm a successful payment. No payment was marked as complete.';
    }
    redirect_to(app_url('views/cart.php'));
}

$paymentMatches = (int) ($transaction['amount'] ?? 0) === (int) $pending['amount']
    && strtoupper((string) ($transaction['currency'] ?? '')) === 'GHS'
    && $transactionEmail === strtolower((string) $pending['email']);

if (!$paymentMatches) {
    unset($_SESSION['paystack_pending']);
    $_SESSION['error'] = 'Paystack payment details did not match this cart. Please contact the store before trying again.';
    redirect_to(app_url('views/cart.php'));
}

$currentCart = $_SESSION['cart'] ?? [];
if (!is_array($currentCart)) {
    $currentCart = [];
}
$normalizedCart = [];
foreach ($currentCart as $productId => $quantity) {
    $productId = filter_var($productId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $quantity = filter_var($quantity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($productId !== false && $quantity !== false) {
        $normalizedCart[$productId] = $quantity;
    }
}
ksort($normalizedCart, SORT_NUMERIC);

if ($normalizedCart === $pending['cart']) {
    unset($_SESSION['cart']);
}

unset($_SESSION['paystack_pending']);
$_SESSION['success'] = 'Payment confirmed. Paystack reference: ' . $reference;
redirect_to(app_url('views/cart.php'));
