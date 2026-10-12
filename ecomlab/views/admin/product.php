<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();
require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

// Load the product being edited.
$editProduct = null;
$editId      = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
if ($editId && $editId > 0) {
    $editProduct = $controller->getProductById($editId);
}

// Read status messages.
$success    = $_SESSION['success'] ?? null; unset($_SESSION['success']);
$error      = $_SESSION['error']   ?? null; unset($_SESSION['error']);

$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();

// Check that product categories and brands are available.
$canAdd = !empty($categories) && !empty($brands);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products — Admin | Shoppin</title>
    <link rel="stylesheet" href="/ecomlab/css/style.css">
</head>
<body class="site-body">
<!-- Shared navigation -->
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Product management -->
<div class="admin-page admin-page--wide">
    <h1><?= $editProduct ? 'Edit Product' : 'Add Product' ?></h1>

    <?php if ($success): ?>
        <div class="flash flash--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flash flash--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Product form -->
    <?php if (!$canAdd && !$editProduct): ?>
        <div class="flash flash--error">
            You must add at least one
            <a href="/ecomlab/views/admin/brand.php">brand</a> and one
            <a href="/ecomlab/views/admin/category.php">category</a> before adding products.
        </div>
    <?php else: ?>

    <?php
        $formAction = $editProduct
            ? '/ecomlab/actions/update_product_action.php'
            : '/ecomlab/actions/add_product_action.php';
    ?>

    <form class="admin-form admin-form--grid" method="POST"
          action="<?= $formAction ?>"
          enctype="multipart/form-data"
          id="productForm" novalidate>

        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id"
                   value="<?= (int) $editProduct['product_id'] ?>">
            <input type="hidden" name="existing_image"
                   value="<?= htmlspecialchars($editProduct['product_image'] ?? '') ?>">
        <?php endif; ?>

        <!-- Title -->
        <div class="form-group form-group--full">
            <label for="product_title">Product Title <span class="req">*</span></label>
            <input type="text" id="product_title" name="product_title" required maxlength="200"
                   value="<?= htmlspecialchars($editProduct['product_title'] ?? '') ?>"
                   placeholder="e.g. Wireless Headphones">
            <div class="field-error" data-error-for="product_title"></div>
        </div>

        <!-- Price -->
        <div class="form-group">
            <label for="product_price">Price (GHS) <span class="req">*</span></label>
            <input type="number" id="product_price" name="product_price"
                   required min="0.01" step="0.01"
                   value="<?= htmlspecialchars($editProduct['product_price'] ?? '') ?>"
                   placeholder="0.00">
            <div class="field-error" data-error-for="product_price"></div>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="product_cat">Category <span class="req">*</span></label>
            <select id="product_cat" name="product_cat" required>
                <option value="">— Select —</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int) $cat['cat_id'] ?>"
                        <?= (isset($editProduct['product_cat']) && (int)$editProduct['product_cat'] === (int)$cat['cat_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['cat_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="field-error" data-error-for="product_cat"></div>
        </div>

        <!-- Brand -->
        <div class="form-group">
            <label for="product_brand">Brand <span class="req">*</span></label>
            <select id="product_brand" name="product_brand" required>
                <option value="">— Select —</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= (int) $brand['brand_id'] ?>"
                        <?= (isset($editProduct['product_brand']) && (int)$editProduct['product_brand'] === (int)$brand['brand_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['brand_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="field-error" data-error-for="product_brand"></div>
        </div>

        <!-- Description -->
        <div class="form-group form-group--full">
            <label for="product_desc">Description</label>
            <textarea id="product_desc" name="product_desc" rows="4"
                      maxlength="500" placeholder="Short product description…"><?= htmlspecialchars($editProduct['product_desc'] ?? '') ?></textarea>
        </div>

        <!-- Keywords -->
        <div class="form-group form-group--full">
            <label for="product_keywords">Keywords
                <span class="label-hint">(comma-separated, used for search)</span>
            </label>
            <input type="text" id="product_keywords" name="product_keywords" maxlength="100"
                   value="<?= htmlspecialchars($editProduct['product_keywords'] ?? '') ?>"
                   placeholder="e.g. wireless, audio, bluetooth">
        </div>

        <!-- Image upload -->
        <div class="form-group form-group--full">
            <label for="product_image">
                Product Image <?= $editProduct ? '(leave blank to keep current)' : '<span class="req">*</span>' ?>
            </label>
            <?php if ($editProduct && !empty($editProduct['product_image'])): ?>
                <div class="image-preview-wrap">
                    <img src="/ecomlab/images/products/<?= htmlspecialchars($editProduct['product_image']) ?>"
                         alt="Current product image" class="image-preview">
                </div>
            <?php endif; ?>
            <input type="file" id="product_image" name="product_image"
                   accept="image/jpeg,image/png,image/gif,image/webp"
                   <?= $editProduct ? '' : 'required' ?>>
            <div class="field-error" data-error-for="product_image"></div>
            <p class="field-hint">Max 2 MB · JPEG, PNG, GIF or WebP only</p>
        </div>

        <!-- Buttons -->
        <div class="form-group form-group--full admin-form__actions">
            <button type="submit">
                <?= $editProduct ? 'Update Product' : 'Add Product' ?>
            </button>
            <?php if ($editProduct): ?>
                <a href="/ecomlab/views/admin/product.php" class="btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <?php endif; ?>

</div>

<script>
// Validate the image before submission.
document.getElementById('productForm')?.addEventListener('submit', function (e) {
    const fileInput = document.getElementById('product_image');
    const errEl     = document.querySelector('[data-error-for="product_image"]');

    if (!fileInput || !fileInput.files.length) return; // optional on edit

    const file    = fileInput.files[0];
    const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const maxSize = 2 * 1024 * 1024;

    if (!allowed.includes(file.type)) {
        e.preventDefault();
        if (errEl) { errEl.textContent = 'Invalid file type. Use JPEG, PNG, GIF or WebP.'; errEl.style.display = 'block'; }
        return;
    }

    if (file.size > maxSize) {
        e.preventDefault();
        if (errEl) { errEl.textContent = 'File is too large. Max 2 MB.'; errEl.style.display = 'block'; }
    }
});
</script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
