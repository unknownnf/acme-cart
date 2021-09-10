<?php

namespace Acme\Sales\Entity;

/**
 * Cart item class
 *
 * @package Acme\Sales\Entity
 */
class CartItem
{

    protected Product $product;

    protected int $quantity;

    /**
     * Cart Item constructor
     *
     * @param Product $product
     * @param int     $quantity
     */
    public function __construct(Product $product, int $quantity = 1)
    {
        $this->setProduct($product)
            ->setQuantity($quantity);
    }

    /**
     * Get product for cart item
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Set product for cart item
     *
     * @param Product $product
     *
     * @return CartItem
     */
    public function setProduct(Product $product): CartItem
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Set item quantity
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Get item quantity
     *
     * @param int $quantity
     *
     * @return CartItem
     */
    public function setQuantity(int $quantity): CartItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get row total for item
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->product->getPrice() * $this->getQuantity();
    }

}
