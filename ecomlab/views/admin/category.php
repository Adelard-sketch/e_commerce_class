<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

// Load the category being edited.
$editCat = null;
$editId  = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
if ($editId && $editId > 0) {
    $editCat = $controller->getCategoryById($editId);
}

// Read status messages.
$success    = $_SESSION['success'] ?? null; unset($_SESSION['success']);
$error      = $_SESSION['error']   ?? null; unset($_SESSION['error']);

$categories = $controller->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories — Admin | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body">
<!-- Shared navigation -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Category management -->
<div class="admin-page">
    <h1><?= $editCat ? 'Edit Category' : 'Manage Categories' ?></h1>

    <?php if ($success): ?>
        <div class="flash flash--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flash flash--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Category form -->
    <?php if ($editCat): ?>
        <form class="admin-form" method="POST" action="/ecomlab/actions/update_category_action.php">
            <input type="hidden" name="cat_id" value="<?= (int) $editCat['cat_id'] ?>">
            <div class="form-group">
                <label for="cat_name">Category Name</label>
                <input type="text" id="cat_name" name="cat_name" required maxlength="100"
                       value="<?= htmlspecialchars($editCat['cat_name']) ?>">
            </div>
            <div class="admin-form__actions">
                <button type="submit">Update Category</button>
                <a href="/ecomlab/views/admin/category.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    <?php else: ?>
        <form class="admin-form" method="POST" action="/ecomlab/actions/add_category_action.php">
            <div class="form-group">
                <label for="cat_name">Category Name</label>
                <input type="text" id="cat_name" name="cat_name" required maxlength="100"
                       placeholder="e.g. Electronics">
            </div>
            <button type="submit">Add Category</button>
        </form>
    <?php endif; ?>

    <!-- Category list -->
    <h2 class="section-heading">All Categories (<?= count($categories) ?>)</h2>

    <?php if (empty($categories)): ?>
        <p class="empty-note">No categories yet. Add the first one above.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= (int) $cat['cat_id'] ?></td>
                            <td><?= htmlspecialchars($cat['cat_name']) ?></td>
                            <td>
                                <a class="btn-edit"
                                   href="/ecomlab/views/admin/category.php?edit_id=<?= (int) $cat['cat_id'] ?>">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
