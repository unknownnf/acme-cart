<?php

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\Shipping\Method\OrderTotalBased;

class ShippingOrderBasedTotalTest extends \Codeception\Test\Unit
{

    public function testCanInstantiate(): void
    {
        $shipping = new OrderTotalBased(0, 50, 4.95);
        $this->assertTrue(is_a($shipping, OrderTotalBased::class));
        $this->assertEquals(0,$shipping->getMinTotal());
        $this->assertEquals(50,$shipping->getMaxTotal());
        $this->assertEquals(4.95,$shipping->getCost());
    }

    public function testIsApplicable(): void
    {
        $shipping = new OrderTotalBased(0, 50, 4.95);
        $cart = $this->make(Cart::class, ['getSubtotal' => 10]);

        $this->assertTrue($shipping->isApplicable($cart));

        $cart = $this->make(Cart::class, ['getSubtotal' => 60]);

        $this->assertFalse($shipping->isApplicable($cart));

        $shipping->setMinTotal(50)->setMaxTotal(90)->setCost(2.95);

        $this->assertTrue($shipping->isApplicable($cart));

        $cart = $this->make(Cart::class, ['getSubtotal' => 10]);
        $this->assertFalse($shipping->isApplicable($cart));

        $cart = $this->make(Cart::class, ['getSubtotal' => 100]);
        $this->assertFalse($shipping->isApplicable($cart));
    }

    public function testShippingCost(): void
    {
        $shipping = new OrderTotalBased(0, 50, 4.95);
        $cart = $this->make(Cart::class, ['getSubtotal' => 10]);

        $this->assertEquals(4.95, $shipping->getShippingCost($cart));

        $shipping->setMinTotal(50)->setMaxTotal(90)->setCost(2.95);
        $this->assertEquals(2.95, $shipping->getShippingCost($cart));


        $shipping->setMinTotal(90)->setMaxTotal(null)->setCost(0);

        $this->assertEquals(0, $shipping->getShippingCost($cart));
    }

    public function testImplementsShippingInterface(): void
    {
        $this->assertContains(
            'Acme\\Sales\\Entity\\Shipping\\ShippingInterface',
            class_implements(get_class(new OrderTotalBased(null, null, 0)))
        );
    }
}