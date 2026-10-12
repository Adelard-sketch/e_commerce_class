<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

// Load the brand being edited.
$editBrand  = null;
$editId     = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
if ($editId && $editId > 0) {
    $editBrand = $controller->getBrandById($editId);
}

// Read status messages.
$success = $_SESSION['success'] ?? null; unset($_SESSION['success']);
$error   = $_SESSION['error']   ?? null; unset($_SESSION['error']);

$brands  = $controller->getAllBrands();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brands — Admin | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body">
<!-- Shared navigation -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Brand management -->
<div class="admin-page">
    <h1><?= $editBrand ? 'Edit Brand' : 'Manage Brands' ?></h1>

    <?php if ($success): ?>
        <div class="flash flash--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flash flash--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Brand form -->
    <?php if ($editBrand): ?>
        <form class="admin-form" method="POST" action="/ecomlab/actions/update_brand_action.php">
            <input type="hidden" name="brand_id" value="<?= (int) $editBrand['brand_id'] ?>">
            <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" id="brand_name" name="brand_name" required maxlength="100"
                       value="<?= htmlspecialchars($editBrand['brand_name']) ?>">
            </div>
            <div class="admin-form__actions">
                <button type="submit">Update Brand</button>
                <a href="/ecomlab/views/admin/brand.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    <?php else: ?>
        <form class="admin-form" method="POST" action="/ecomlab/actions/add_brand_action.php">
            <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" id="brand_name" name="brand_name" required maxlength="100"
                       placeholder="e.g. Nike">
            </div>
            <button type="submit">Add Brand</button>
        </form>
    <?php endif; ?>

    <!-- Brand list -->
    <h2 class="section-heading">All Brands (<?= count($brands) ?>)</h2>

    <?php if (empty($brands)): ?>
        <p class="empty-note">No brands yet. Add the first one above.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Brand Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $brand): ?>
                        <tr>
                            <td><?= (int) $brand['brand_id'] ?></td>
                            <td><?= htmlspecialchars($brand['brand_name']) ?></td>
                            <td>
                                <a class="btn-edit"
                                   href="/ecomlab/views/admin/brand.php?edit_id=<?= (int) $brand['brand_id'] ?>">
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
