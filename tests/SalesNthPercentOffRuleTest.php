<?php

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\Sales\Rule\NthPercentOffRule;
use Acme\Sales\Entity\CartItem;
use Acme\Sales\Entity\Product;

class SalesNthPercentOffRuleTest extends \Codeception\Test\Unit
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanInstantiate(): void
    {
        $rule = new NthPercentOffRule('R01', 2, 0.5);
        $this->assertTrue(is_a($rule, NthPercentOffRule::class));
        $this->assertEquals('R01', $rule->getCode());
        $this->assertEquals(2, $rule->getStep());
        $this->assertEquals(0.5, $rule->getMultiplier());
    }

    public function testImplementsRuleInterface(): void
    {
        $this->assertContains(
            'Acme\\Sales\\Entity\\Sales\\RuleInterface',
            class_implements(get_class(new NthPercentOffRule('R01', 2, 0.5)))
        );
    }

    public function testIsApplicable(): void
    {
        $rule = new NthPercentOffRule('R01', 2, 0.5);
        $cart = new Cart();
        $this->assertFalse($rule->isApplicable($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertFalse($rule->isApplicable($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertTrue($rule->isApplicable($cart));
    }

    public function testDiscountAmounts(): void
    {
        $rule = new NthPercentOffRule('R01', 2, 0.5);
        $cart = new Cart();
        $this->assertEquals(0, $rule->getDiscount($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertEquals(0, $rule->getDiscount($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertEquals(32.95 / 2, $rule->getDiscount($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertEquals(32.95 / 2, $rule->getDiscount($cart));

        $cart->addItem(
            new CartItem(new Product('R01', 'Red Widget', 32.95), 1)
        );
        $this->assertEquals(32.95, $rule->getDiscount($cart));
    }
}