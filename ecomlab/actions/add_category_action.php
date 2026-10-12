<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/category.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Category name cannot be empty.';
    redirect_to('/ecomlab/views/admin/category.php');
}

$controller = new ProductController();

if ($controller->addCategory($name)) {
    $_SESSION['success'] = 'Category "' . htmlspecialchars($name) . '" added successfully.';
} else {
    $_SESSION['error'] = 'Could not add category. Please try again.';
}

redirect_to('/ecomlab/views/admin/category.php');
