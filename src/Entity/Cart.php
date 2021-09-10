<?php

namespace Acme\Sales\Entity;

use Acme\Sales\Entity\Sales\RuleInterface;
use Acme\Sales\Entity\Shipping\ShippingInterface;
use Acme\Sales\Exception\CartException;
use Acme\Sales\Repository\CartItemRepository;
use Acme\Sales\Repository\ProductRepository;
use Acme\Sales\Repository\Repository;

/**
 * Cart class
 *
 * @package Acme\Sales\Entity
 */
class Cart
{

    protected float $discountTotal = 0;
    protected float $shippingTotal = 0;
    protected float $grandTotal = 0;

    protected array $shippingMethods = [];
    protected array $salesRules = [];

    protected CartItemRepository $items;
    protected ProductRepository $catalog;

    /**
     * Cart constructor
     */
    public function __construct()
    {
        $this->items = new CartItemRepository();
        $this->catalog = new ProductRepository();
    }

    /**
     * Add cart item to cart
     *
     * @param CartItem $cartItem
     *
     * @return Cart
     */
    public function addItem(CartItem $cartItem): Cart
    {
        $code = $cartItem->getProduct()->getCode();
        if ($item = $this->getItems()->get($code)) {
            $item->setQuantity($item->getQuantity() + $cartItem->getQuantity());
        } else {
            $this->items->add($cartItem, $code);
        }
        $this->calculateTotals();
        return $this;
    }

    /**
     * Remove item with code from cart
     *
     * @param string $code
     *
     * @return Cart
     */
    public function removeProduct(string $code): Cart
    {
        $this->items->delete($code);
        $this->calculateTotals();
        return $this;

    }

    /**
     * Get items repository
     *
     * @return Repository
     */
    public function getItems(): Repository
    {
        return $this->items;
    }

    /**
     * Get product repository
     *
     * @return ProductRepository
     */
    public function getCatalog(): ProductRepository
    {
        return $this->catalog;
    }

    /**
     * Add product to the cart by code
     *
     * @param string $code     Code of the product to add.
     * @param int    $quantity (optional) Quantity to add to the cart.
     *
     * @return Cart
     * @throws CartException
     */
    public function addProduct(string $code, int $quantity = 1): Cart
    {
        $product = $this->getCatalog()->get($code);
        if ($product instanceof Product) {
            $this->addItem(new CartItem($product, $quantity));
        } else {
            throw new CartException('Product does not exist');
        }
        return $this;
    }

    /**
     * Attach a sales rule to the cart
     *
     * @param RuleInterface $cartRule
     *
     * @return Cart
     */
    public function addSalesRule(RuleInterface $cartRule): Cart
    {
        $this->salesRules[] = $cartRule;
        return $this;
    }

    /**
     * Attach a shipping method to the cart
     *
     * @param ShippingInterface $shippingMethod
     *
     * @return Cart
     */
    public function addShippingMethod(ShippingInterface $shippingMethod): Cart
    {
        $this->shippingMethods[] = $shippingMethod;
        return $this;
    }

    /**
     * Get the sale rules
     *
     * @return array
     */
    public function getSalesRules(): array
    {
        return $this->salesRules;
    }

    /**
     * Get the shipping methods
     *
     * @return array
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    /**
     * Get the subtotal without any discounts
     *
     * @return float
     */
    public function getSubTotal(): float
    {
        $total = 0.0;
        foreach ($this->getItems() as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return round($total, 2);
    }

    /**
     * Get calculated grand total
     *
     * @return float
     */
    public function getGrandTotal(): float
    {
        return round($this->grandTotal, 2);
    }

    /**
     * Get calculated shipping total
     *
     * @return float
     */
    public function getShippingTotal(): float
    {
        return round($this->shippingTotal, 2);
    }

    /**
     * Get discount total
     *
     * @return float
     */
    public function getDiscountTotal(): float
    {
        return round($this->discountTotal, 2);
    }

    /**
     * Calculate totals for the current item list.
     */
    protected function calculateTotals(): void
    {
        $this->calculateDiscountTotal();
        $this->calculateShippingTotal();
        $this->calculateGrandTotal();
    }

    /**
     * Calculate grand total from subtotals.
     *
     * @return $this
     */
    protected function calculateGrandTotal(): Cart
    {
        $this->grandTotal = $this->getSubTotal() + $this->getShippingTotal()
            - $this->getDiscountTotal();
        return $this;
    }

    /**
     * Calculate shipping total for the cart based on defined methods.
     *
     * @return Cart
     */
    protected function calculateShippingTotal(): Cart
    {
        $shippingTotal = 0;
        foreach ($this->getShippingMethods() as $shippingMethod) {
            if ($shippingMethod->isApplicable($this)) {
                $shippingTotal = $shippingMethod->getShippingCost($this);
                break;
            }
        }
        $this->shippingTotal = $shippingTotal;
        return $this;
    }

    /**
     * Calculate discount for cart based on defined methods.
     *
     * @return Cart
     */
    protected function calculateDiscountTotal(): Cart
    {
        $discount = 0;
        foreach ($this->getSalesRules() as $cartRule) {
            if ($cartRule->isApplicable($this)) {
                $discount += $cartRule->getDiscount($this);
            }
        }
        $this->discountTotal = $discount;
        return $this;
    }


}