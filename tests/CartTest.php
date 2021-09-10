<?php

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\CartItem;
use Acme\Sales\Entity\Product;
use Acme\Sales\Entity\Sales\Rule\NthPercentOffRule;
use Acme\Sales\Entity\Shipping\Method\OrderTotalBased;
use Acme\Sales\Exception\CartException;
use Acme\Sales\Repository\CartItemRepository;
use Acme\Sales\Repository\ProductRepository;

class CartTest extends \Codeception\Test\Unit
{

    protected Cart $cart;

    /**
     * Setup for the cart tests.
     */
    public function setUp(): void
    {
        $cart = new Cart();
        $cart->getCatalog()->add(new Product('R01', 'Red Widget', 32.95));
        $cart->getCatalog()->add(new Product('G01', 'Green Widget', 24.95));
        $cart->getCatalog()->add(new Product('B01', 'Blue Widget', 7.95));

        $cart->addSalesRule(
            new NthPercentOffRule('R01', 2, 0.5)
        );

        $cart->addShippingMethod(
            new OrderTotalBased(0, 50, 4.95)
        );
        $cart->addShippingMethod(
            new OrderTotalBased(50, 90, 2.95)
        );
        $cart->addShippingMethod(
            new OrderTotalBased(90, null, 0)
        );
        $this->cart = $cart;
    }

    /**
     * Test cart instantiation
     */
    public function testCanInstantiate(): void
    {
        $cart = new Cart();
        $this->assertTrue(is_a($cart, Cart::class));
        $this->assertTrue(is_a($cart->getItems(), CartItemRepository::class));
        $this->assertTrue(is_a($cart->getCatalog(), ProductRepository::class));
    }

    /**
     * Test adding of items
     */
    public function testAddItem(): void
    {
        $this->assertCount(0, $this->cart->getItems());

        $this->cart->addItem(
            new CartItem($this->cart->getCatalog()->get('R01'))
        );
        $this->assertCount(1, $this->cart->getItems());

        $this->cart->addItem(
            new CartItem($this->cart->getCatalog()->get('R01'))
        );
        $this->assertCount(1, $this->cart->getItems());

        $this->cart->addItem(
            new CartItem($this->cart->getCatalog()->get('B01'))
        );
        $this->assertCount(2, $this->cart->getItems());
    }

    /**
     * Test adding products to the cart.
     *
     * @throws CartException
     */
    public function testAddProduct(): void
    {
        $this->assertCount(0, $this->cart->getItems());

        $this->cart->addProduct('R01');
        $this->assertCount(1, $this->cart->getItems());

        $this->cart->addProduct('R01');
        $this->assertCount(1, $this->cart->getItems());

        $this->cart->addProduct('B01');
        $this->assertCount(2, $this->cart->getItems());
    }

    /**
     * Test product removal from the cart.
     *
     * @throws CartException
     */
    public function testRemoveItem(): void
    {

        $this->cart->addProduct('R01');
        $this->cart->addProduct('B01');
        $this->cart->removeProduct('R01');
        $this->assertCount(1, $this->cart->getItems());
    }

    /**
     * Test exception for adding non existant product to the cart.
     *
     * @throws CartException
     */
    public function testCannotAddMissingProduct(): void
    {
        $this->expectException(CartException::class);
        $this->cart->addProduct('invalid');
    }

    /**
     * Test adding shipping methods to the cart.
     */
    public function testAddShippingMethod(): void
    {
        $cart = new Cart();
        $this->assertCount(0, $cart->getShippingMethods());
        $cart->addShippingMethod(
            new OrderTotalBased(0, 50, 4.95)
        );
        $this->assertCount(1, $cart->getShippingMethods());
        $cart->addShippingMethod(
            new OrderTotalBased(50, 90, 2.95)
        );
        $this->assertCount(2, $cart->getShippingMethods());
    }

    /**
     * Test adding sales rules to the cart.
     */
    public function testAddSalesRules(): void
    {
        $cart = new Cart();
        $this->assertCount(0, $cart->getSalesRules());

        $cart->addSalesRule(
            new NthPercentOffRule('R01', 2, 0.5)
        );
        $this->assertCount(1, $cart->getSalesRules());

        $cart->addSalesRule(
            new NthPercentOffRule('B01', 3, 0.25)
        );
        $this->assertCount(2, $cart->getSalesRules());
    }

    /**
     * Test totals calculated by the add operations.
     *
     * @throws CartException
     */
    public function testTotals(): void
    {
        $cart = $this->cart;
        $cart->addProduct('R01');
        $this->assertEquals(32.95, $cart->getSubTotal());
        $this->assertEquals(4.95, $cart->getShippingTotal());
        $this->assertEquals(0, $cart->getDiscountTotal());
        $this->assertEquals(37.9, $cart->getGrandTotal());

        $cart->addProduct('R01');
        $cart->addProduct('B01');
        $this->assertEquals(73.85, $cart->getSubTotal());
        $this->assertEquals(2.95, $cart->getShippingTotal());
        $this->assertEquals(16.48, $cart->getDiscountTotal());
        $this->assertEquals(60.32, $cart->getGrandTotal());
    }

    /**
     * Test the first scenario, one blue widget and one red widget
     *
     * @throws CartException
     */
    public function testScenarioOneBlueOneGreen(): void
    {
        $cart = $this->cart;
        $cart->addProduct('B01');
        $cart->addProduct('G01');
        $this->assertEquals(37.85, $cart->getGrandTotal());
    }

    /**
     * Test the second scenario, two red widgets.
     *
     * @throws CartException
     */
    public function testScenarioTwoRed(): void
    {
        $cart = $this->cart;
        $cart->addProduct('R01');
        $cart->addProduct('R01');
        $this->assertEquals(54.37, $cart->getGrandTotal());
    }

    /**
     * Test the third scenario, one green widget and one red widget.
     *
     * @throws CartException
     */
    public function testScenarioOneGreenOneRed(): void
    {
        $cart = $this->cart;
        $cart->addProduct('G01');
        $cart->addProduct('R01');
        $this->assertEquals(60.85, $cart->getGrandTotal());
    }

    /**
     * Test fourth scenario, two blue widgets and three red widgets.
     *
     * @throws CartException
     */
    public function testScenarioTwoBlueThreeRed(): void
    {
        $cart = $this->cart;
        $cart->addProduct('B01');
        $cart->addProduct('B01');
        $cart->addProduct('R01');
        $cart->addProduct('R01');
        $cart->addProduct('R01');
        $this->assertEquals(98.27, $cart->getGrandTotal());

    }
}