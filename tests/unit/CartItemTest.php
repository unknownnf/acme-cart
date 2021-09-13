<?php

use Acme\Sales\Entity\CartItem;
use Acme\Sales\Entity\Product;

class CartItemTest extends \Codeception\Test\Unit
{

    /**
     * Test cart item instantiation
     */
    public function testCanInstantiate(): void
    {
        $product = new Product('R01', 'Red Widget', 32.95);
        $cartItem = new CartItem($product, 1);
        $this->assertTrue(is_a($cartItem, CartItem::class));
        $this->assertTrue(is_a($cartItem->getProduct(), Product::class));
        $this->assertEquals(1, $cartItem->getQuantity());
    }

    /**
     * Test cart item total calculation
     */
    public function testCartItemTotal(): void
    {
        $product = new Product('R01', 'Red Widget', 32.95);
        $cartItem = new CartItem($product, 1);
        $this->assertEquals(32.95, $cartItem->getTotal());

        $cartItem->setQuantity(2);
        $this->assertEquals(65.9, $cartItem->getTotal());
    }
}