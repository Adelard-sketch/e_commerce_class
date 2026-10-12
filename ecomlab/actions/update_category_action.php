<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('/ecomlab/views/admin/category.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$cat_id   = filter_input(INPUT_POST, 'cat_id',   FILTER_VALIDATE_INT);
$cat_name = trim(strip_tags($_POST['cat_name'] ?? ''));

if (!$cat_id || $cat_id < 1) {
    $_SESSION['error'] = 'Invalid category ID.';
    redirect_to('/ecomlab/views/admin/category.php');
}

if ($cat_name === '') {
    $_SESSION['error'] = 'Category name cannot be empty.';
    redirect_to('/ecomlab/views/admin/category.php?edit_id=' . $cat_id);
}

$controller = new ProductController();

if ($controller->updateCategory($cat_id, $cat_name)) {
    $_SESSION['success'] = 'Category updated successfully.';
} else {
    $_SESSION['error'] = 'Could not update category. Please try again.';
}

redirect_to('/ecomlab/views/admin/category.php');
