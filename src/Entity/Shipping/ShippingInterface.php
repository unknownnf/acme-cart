<?php

namespace Acme\Sales\Entity\Shipping;

use Acme\Sales\Entity\Cart;

/**
 * Shipping method interface
 *
 * @package Acme\Sales\Entity\Shipping
 */
interface ShippingInterface
{
    /**
     * Check if the shipping method applies to the specified cart.
     *
     * @param Cart $cart
     *
     * @return bool
     */
    public function isApplicable(Cart $cart): bool;

    /**
     * Get shipping cost for the shipping method.
     *
     * @param Cart $cart
     *
     * @return float
     */
    public function getShippingCost(Cart $cart): float;
}