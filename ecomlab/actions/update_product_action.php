<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/product.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

// ── Sanitise ─────────────────────────────────────────────────────────────────
$product_id      = filter_input(INPUT_POST, 'product_id',    FILTER_VALIDATE_INT);
$cat             = filter_input(INPUT_POST, 'product_cat',   FILTER_VALIDATE_INT);
$brand           = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);
$title           = trim(strip_tags($_POST['product_title']    ?? ''));
$price           = filter_input(INPUT_POST, 'product_price',  FILTER_VALIDATE_FLOAT);
$desc            = trim(strip_tags($_POST['product_desc']     ?? ''));
$keywords        = trim(strip_tags($_POST['product_keywords'] ?? ''));
$existing_image  = trim(strip_tags($_POST['existing_image']   ?? ''));

if (!$product_id || $product_id < 1) {
    $_SESSION['error'] = 'Invalid product ID.';
    redirect_to('/ecomlab/views/admin/product.php');
}

if (!$cat || !$brand || $title === '' || $price === false || $price <= 0) {
    $_SESSION['error'] = 'Please fill in all required fields correctly.';
    redirect_to('/ecomlab/views/admin/product.php?edit_id=' . $product_id);
}

// ── Optional new image upload ────────────────────────────────────────────────
$filename = $existing_image; // default: keep old filename

if (!empty($_FILES['product_image']['name'])) {
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxBytes = 2 * 1024 * 1024;

    if (!in_array($_FILES['product_image']['type'], $allowed, true)) {
        $_SESSION['error'] = 'Invalid image type. Allowed: JPEG, PNG, GIF, WebP.';
        redirect_to('/ecomlab/views/admin/product.php?edit_id=' . $product_id);
    }

    if ($_FILES['product_image']['size'] > $maxBytes) {
        $_SESSION['error'] = 'Image must be 2 MB or smaller.';
        redirect_to('/ecomlab/views/admin/product.php?edit_id=' . $product_id);
    }

    $filename   = uniqid('', true) . '_' . basename($_FILES['product_image']['name']);
    $uploadPath = __DIR__ . '/../images/products/' . $filename;

    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
        $_SESSION['error'] = 'Image upload failed. Check folder permissions.';
        redirect_to('/ecomlab/views/admin/product.php?edit_id=' . $product_id);
    }

    // Remove the old image file if it exists
    $oldPath = __DIR__ . '/../images/products/' . $existing_image;
    if ($existing_image && file_exists($oldPath)) {
        @unlink($oldPath);
    }
}

// ── Persist ──────────────────────────────────────────────────────────────────
$controller = new ProductController();

if ($controller->updateProduct($product_id, $cat, $brand, $title, $price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product updated successfully.';
} else {
    $_SESSION['error'] = 'Could not update product. Please try again.';
}

redirect_to('/ecomlab/views/admin/product.php');
