<?php
require __DIR__ . '/vendor/autoload.php';

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\Product;
use Acme\Sales\Entity\Sales\Rule\NthPercentOffRule;
use Acme\Sales\Entity\Shipping\Method\OrderTotalBased;

// Create a new cart
$cart = new Cart();

// Instantiate catalog for the cart
$cart->getCatalog()->add(new Product('R01', 'Red Widget', 32.95));
$cart->getCatalog()->add(new Product('G01', 'Green Widget', 24.95));
$cart->getCatalog()->add(new Product('B01', 'Blue Widget', 7.95));

// Add a sales rule, every second product with code will be half price.
$cart->addSalesRule(
    new NthPercentOffRule('R01', 2, 0.5)
);

// Add available shipping methods to the cart.
$cart->addShippingMethod(
    new OrderTotalBased(0, 50, 4.95)
);
$cart->addShippingMethod(
    new OrderTotalBased(50, 90, 2.95)
);
$cart->addShippingMethod(
    new OrderTotalBased(90, null, 0)
);

$scenarios = static function (?int $scenario) use ($cart) {
    switch ($scenario) {
        default:
            $cart->addProduct('B01');
            $cart->addProduct('G01');
            break;
        case 2:
            $cart->addProduct('R01');
            $cart->addProduct('R01');
            break;
        case 3:
            $cart->addProduct('G01');
            $cart->addProduct('R01');
            break;
        case 4:
            $cart->addProduct('B01');
            $cart->addProduct('B01');
            $cart->addProduct('R01');
            $cart->addProduct('R01');
            $cart->addProduct('R01');
            break;
    }
};

$renderer = static function () use ($cart) {
    ?>
    <table class="table table-bordered tbl-cart">
        <thead>
        <tr>
            <td>Product Name</td>
            <td>Code</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Sub Total</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart->getItems() as $item): ?>
            <tr>
                <td><?= $item->getProduct()->getName() ?></td>
                <td><?= $item->getProduct()->getCode() ?></td>
                <td><?= $item->getQuantity() ?></td>
                <td><?= $item->getProduct()->getPrice() ?></td>
                <td>$ <?= $item->getTotal() ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($cart->getShippingTotal()): ?>
            <tr>
                <td colspan="4">Shipping</td>
                <td colspan="1"><b>$ <?= $cart->getShippingTotal() ?></b></td>
            </tr>
        <?php endif; ?>
        <?php if ($cart->getDiscountTotal()): ?>
            <tr>
                <td colspan="4">Discount</td>
                <td colspan="1"><b>$ <?= $cart->getDiscountTotal() ?></b></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td colspan="4">Total</td>
            <td colspan="1"><b>$ <?= $cart->getGrandTotal() ?></b></td>
        </tr>
        </tbody>
    </table>

    <a href="/1">Example 1</a> |
    <a href="/2">Example 2</a> |
    <a href="/3">Example 3</a> |
    <a href="/4">Example 4</a>
    <?php
};
