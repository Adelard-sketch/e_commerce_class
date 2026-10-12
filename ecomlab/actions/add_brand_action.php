<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/brand.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Brand name cannot be empty.';
    redirect_to('/ecomlab/views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->addBrand($name)) {
    $_SESSION['success'] = 'Brand "' . htmlspecialchars($name) . '" added successfully.';
} else {
    $_SESSION['error'] = 'Could not add brand. Please try again.';
}

redirect_to('/ecomlab/views/admin/brand.php');
