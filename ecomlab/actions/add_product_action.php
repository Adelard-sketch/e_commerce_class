<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/product.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

// ── Sanitise text fields ─────────────────────────────────────────────────────
$cat      = filter_input(INPUT_POST, 'product_cat',   FILTER_VALIDATE_INT);
$brand    = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);
$title    = trim(strip_tags($_POST['product_title']    ?? ''));
$price    = filter_input(INPUT_POST, 'product_price',  FILTER_VALIDATE_FLOAT);
$desc     = trim(strip_tags($_POST['product_desc']     ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

// ── Basic validation ─────────────────────────────────────────────────────────
if (!$cat || !$brand || $title === '' || $price === false || $price <= 0) {
    $_SESSION['error'] = 'Please fill in all required fields correctly.';
    redirect_to('/ecomlab/views/admin/product.php');
}

// ── Image upload ─────────────────────────────────────────────────────────────
$allowed   = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxBytes  = 2 * 1024 * 1024; // 2 MB

if (empty($_FILES['product_image']['name'])) {
    $_SESSION['error'] = 'A product image is required.';
    redirect_to('/ecomlab/views/admin/product.php');
}

if (!in_array($_FILES['product_image']['type'], $allowed, true)) {
    $_SESSION['error'] = 'Invalid image type. Allowed: JPEG, PNG, GIF, WebP.';
    redirect_to('/ecomlab/views/admin/product.php');
}

if ($_FILES['product_image']['size'] > $maxBytes) {
    $_SESSION['error'] = 'Image must be 2 MB or smaller.';
    redirect_to('/ecomlab/views/admin/product.php');
}

$filename   = uniqid('', true) . '_' . basename($_FILES['product_image']['name']);
$uploadPath = __DIR__ . '/../images/products/' . $filename;

if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
    $_SESSION['error'] = 'Image upload failed. Check folder permissions.';
    redirect_to('/ecomlab/views/admin/product.php');
}

// ── Persist ──────────────────────────────────────────────────────────────────
$controller = new ProductController();

if ($controller->addProduct($cat, $brand, $title, $price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product "' . htmlspecialchars($title) . '" added successfully.';
    redirect_to('/ecomlab/views/admin/product.php');
} else {
    // Clean up uploaded file so orphaned images don't pile up
    @unlink($uploadPath);
    $_SESSION['error'] = 'Could not save product. Please try again.';
    redirect_to('/ecomlab/views/admin/product.php');
}
