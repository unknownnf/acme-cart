<?php

use Acme\Sales\Entity\Product;

class ProductTest extends \Codeception\Test\Unit
{

    /**
     * Test product instantiation
     */
    public function testCanInstantiate(): void
    {
        $product = new Product('R01', 'Red Widget', 32.95);
        $this->assertTrue(is_a($product, Product::class));
        $this->assertEquals('R01', $product->getCode());
        $this->assertEquals('Red Widget', $product->getName());
        $this->assertEquals(32.95, $product->getPrice());
    }
}
