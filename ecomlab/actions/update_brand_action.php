<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/brand.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$brand_id   = filter_input(INPUT_POST, 'brand_id',   FILTER_VALIDATE_INT);
$brand_name = trim(strip_tags($_POST['brand_name'] ?? ''));

if (!$brand_id || $brand_id < 1) {
    $_SESSION['error'] = 'Invalid brand ID.';
    redirect_to('/ecomlab/views/admin/brand.php');
}

if ($brand_name === '') {
    $_SESSION['error'] = 'Brand name cannot be empty.';
    redirect_to('/ecomlab/views/admin/brand.php?edit_id=' . $brand_id);
}

$controller = new ProductController();

if ($controller->updateBrand($brand_id, $brand_name)) {
    $_SESSION['success'] = 'Brand updated successfully.';
} else {
    $_SESSION['error'] = 'Could not update brand. Please try again.';
}

redirect_to('/ecomlab/views/admin/brand.php');
