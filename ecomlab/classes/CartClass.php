<?php

class CartClass
{
    private $products;

    public function __construct(ProductController $products)
    {
        $this->products = $products;
    }

    public function addProduct($productId, $quantity)
    {
        if ($productId < 1 || $quantity < 1 || !$this->products->getProductById($productId)) {
            return false;
        }

        $cart = $this->getCart();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $_SESSION['cart'] = $cart;

        return true;
    }

    public function updateQuantity($productId, $quantity)
    {
        $cart = $this->getCart();
        if ($productId < 1 || $quantity < 1 || !isset($cart[$productId])) {
            return false;
        }

        $cart[$productId] = $quantity;
        $_SESSION['cart'] = $cart;

        return true;
    }

    public function removeProduct($productId)
    {
        $cart = $this->getCart();
        if (!isset($cart[$productId])) {
            return false;
        }

        unset($cart[$productId]);
        $_SESSION['cart'] = $cart;

        return true;
    }

    public function getItems()
    {
        $cart = $this->getCart();
        $products = $this->products->getProductsByIds(array_keys($cart));
        $items = [];

        foreach ($products as $product) {
            $productId = (int) $product['product_id'];
            if (!isset($cart[$productId])) {
                continue;
            }

            $product['quantity'] = $cart[$productId];
            $product['line_total'] = (float) $product['product_price'] * $cart[$productId];
            $items[] = $product;
        }

        return $items;
    }

    private function getCart()
    {
        $sessionCart = $_SESSION['cart'] ?? [];
        if (!is_array($sessionCart)) {
            return [];
        }

        $cart = [];
        foreach ($sessionCart as $productId => $quantity) {
            $productId = filter_var($productId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($productId !== false && $quantity !== false) {
                $cart[$productId] = $quantity;
            }
        }

        return $cart;
    }
}
