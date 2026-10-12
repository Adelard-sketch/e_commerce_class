<?php

require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database
{
    // ── BRANDS ──────────────────────────────────────────────────

    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";
        return $this->execute($sql, [trim($name)]);
    }

    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        return $this->fetchAll($sql) ?: [];
    }

    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        return $this->fetchOne($sql, [(int) $id]);
    }

    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        return $this->execute($sql, [trim($name), (int) $id]);
    }

    // ── CATEGORIES ──────────────────────────────────────────────

    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";
        return $this->execute($sql, [trim($name)]);
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->fetchAll($sql) ?: [];
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";
        return $this->fetchOne($sql, [(int) $id]);
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        return $this->execute($sql, [trim($name), (int) $id]);
    }

    // ── PRODUCTS ────────────────────────────────────────────────

    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "INSERT INTO products
                    (product_cat, product_brand, product_title, product_price,
                     product_desc, product_image, product_keywords)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            (int)   $cat,
            (int)   $brand,
            trim($title),
            (float) $price,
            trim($desc),
            $image_filename,
            trim($keywords),
        ]);
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "UPDATE products
                SET product_cat      = ?,
                    product_brand    = ?,
                    product_title    = ?,
                    product_price    = ?,
                    product_desc     = ?,
                    product_image    = ?,
                    product_keywords = ?
                WHERE product_id = ?";
        return $this->execute($sql, [
            (int)   $cat,
            (int)   $brand,
            trim($title),
            (float) $price,
            trim($desc),
            $image_filename,
            trim($keywords),
            (int)   $id,
        ]);
    }

    public function getProductById($id)
    {
        $sql = "SELECT p.*,
                       c.cat_name,
                       b.brand_name
                FROM products p
                JOIN categories c ON c.cat_id   = p.product_cat
                JOIN brands     b ON b.brand_id = p.product_brand
                WHERE p.product_id = ?";
        return $this->fetchOne($sql, [(int) $id]);
    }

    public function getFeaturedProducts($limit = 6)
    {
        $limit = (int) $limit;
        $sql   = "SELECT p.*, c.cat_name, b.brand_name
                  FROM products p
                  JOIN categories c ON c.cat_id   = p.product_cat
                  JOIN brands     b ON b.brand_id = p.product_brand
                  ORDER BY RAND()
                  LIMIT {$limit}";
        return $this->fetchAll($sql) ?: [];
    }

    public function getProductsByCategory($cat_id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON c.cat_id   = p.product_cat
                JOIN brands     b ON b.brand_id = p.product_brand
                WHERE p.product_cat = ?";
        return $this->fetchAll($sql, [(int) $cat_id]) ?: [];
    }

    public function getProductsByBrand($brand_id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON c.cat_id   = p.product_cat
                JOIN brands     b ON b.brand_id = p.product_brand
                WHERE p.product_brand = ?";
        return $this->fetchAll($sql, [(int) $brand_id]) ?: [];
    }

    public function searchProducts($query)
    {
        $like = '%' . trim($query) . '%';
        $sql  = "SELECT p.*, c.cat_name, b.brand_name
                 FROM products p
                 JOIN categories c ON c.cat_id   = p.product_cat
                 JOIN brands     b ON b.brand_id = p.product_brand
                 WHERE p.product_title    LIKE ?
                    OR p.product_keywords LIKE ?";
        return $this->fetchAll($sql, [$like, $like]) ?: [];
    }
}
