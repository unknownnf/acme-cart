<?php

namespace Acme\Sales\Entity\Shipping\Method;

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\Shipping\ShippingInterface;

/**
 * Shipping method based on order total.
 *
 * @package Acme\Sales\Entity\Shipping\Method
 */
class OrderTotalBased implements ShippingInterface
{

    protected ?float $minTotal;
    protected ?float $maxTotal;
    protected float $cost;

    /**
     * Shipping method constructor
     *
     * @param float|null $minTotal
     * @param float|null $maxTotal
     * @param float      $cost
     */
    public function __construct(?float $minTotal, ?float $maxTotal,
        float $cost = 0
    ) {
        $this->setMinTotal($minTotal)->setMaxTotal($maxTotal)->setCost($cost);
    }


    /**
     * Set shipping method Min Total
     *
     * @param float|null $minTotal
     *
     * @return OrderTotalBased
     */
    public function setMinTotal(?float $minTotal): OrderTotalBased
    {
        $this->minTotal = $minTotal;

        return $this;
    }

    /**
     * Get shipping method Min Total
     *
     * @return float|null
     */
    public function getMinTotal(): ?float
    {
        return $this->minTotal;
    }

    /**
     * Set shipping method Max Total
     *
     * @param float|null $maxTotal
     *
     * @return OrderTotalBased
     */
    public function setMaxTotal(?float $maxTotal): OrderTotalBased
    {
        $this->maxTotal = $maxTotal;

        return $this;
    }

    /**
     * Get shipping method Max Total
     *
     * @return float|null
     */
    public function getMaxTotal(): ?float
    {
        return $this->maxTotal;
    }

    /**
     * Set shipping method cost.
     *
     * @param float $cost
     *
     * @return OrderTotalBased
     */
    public function setCost(float $cost): OrderTotalBased
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * Get shipping method cost.
     *
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @inheritDoc
     */
    public function getShippingCost(Cart $cart): float
    {
        return $this->getCost();
    }


    /**
     * Check if this shipping method is applicable to the cart.
     *
     * @param Cart $cart
     *
     * @return bool
     */
    public function isApplicable(Cart $cart): bool
    {
        $subTotal = $cart->getSubTotal() - $cart->getDiscountTotal();

        if ($this->getMinTotal() === null
            && $subTotal <= $this->getMaxTotal()
        ) {
            return true;
        }

        if ($this->getMaxTotal() === null && $subTotal > $this->getMinTotal()) {
            return true;
        }

        return $subTotal > $this->getMinTotal()
            && $subTotal < +$this->getMaxTotal();
    }
}