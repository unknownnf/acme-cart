<?php

namespace Acme\Sales\Entity\Sales;

use Acme\Sales\Entity\Cart;

interface RuleInterface
{
    /**
     * Check if the rule applies to the cart.
     *
     * @param Cart $cart
     *
     * @return bool
     */
    public function isApplicable(Cart $cart): bool;

    /**
     * Get the discount that has to be applied to the cart.
     *
     * @param Cart $cart
     *
     * @return float
     */
    public function getDiscount(Cart $cart): float;
}