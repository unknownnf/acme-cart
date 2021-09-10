<?php

namespace Acme\Sales\Entity\Sales\Rule;

use Acme\Sales\Entity\Cart;
use Acme\Sales\Entity\Sales\RuleInterface;
use Acme\Sales\Entity\CartItem;

/**
 * Sales rule for nth product percent off
 *
 * @package Acme\Sales\Entity\Sales\Rule
 */

class NthPercentOffRule implements RuleInterface
{

    protected string $code;

    protected int $step;

    protected float $multiplier;

    /**
     * Rule constructor
     *
     * @param string $code Product code that the rule applies to.
     * @param int    $step Every n product apply multiplier.
     * @param float  $multiplier Multiplier to apply to.
     */
    public function __construct(string $code, int $step, float $multiplier)
    {
        $this->setCode($code)
            ->setStep($step)
            ->setMultiplier($multiplier);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Cart $cart): bool
    {
        return $this->getApplicableItem($cart) instanceof CartItem;
    }

    /**
     * @inheritDoc
     */
    public function getDiscount(Cart $cart): float
    {
        $price = 0;
        if ($item = $this->getApplicableItem($cart)) {
            $price = floor($item->getQuantity() / $this->getStep())
                * ($item->getProduct()->getPrice() * $this->getMultiplier());
        }
        return $price;
    }

    /**
     * Get the item this rule applies to if it's in the cart.
     *
     * @param Cart $cart
     *
     * @return null|CartItem
     */
    private function getApplicableItem(Cart $cart): ?CartItem
    {
        $item = null;
        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getQuantity() >= $this->getStep()
                && $this->getCode() === $cartItem->getProduct()->getCode()
            ) {
                $item = $cartItem;
            }
        }

        return $item;
    }

    /**
     * Get the product code this rule applies to.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the product code this rule applies to.
     *
     * @param string $code
     *
     * @return NthPercentOffRule
     */
    public function setCode(string $code): NthPercentOffRule
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get stepping for the rule.
     *
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * Set stepping for the rule.
     *
     * @param int $step
     *
     * @return NthPercentOffRule
     */
    public function setStep(int $step): NthPercentOffRule
    {
        $this->step = $step;
        return $this;
    }

    /**
     * Get rule multiplier
     *
     * @return float
     */
    public function getMultiplier(): float
    {
        return $this->multiplier;
    }

    /**
     * Set rule multiplier
     *
     * @param float $multiplier
     *
     * @return NthPercentOffRule
     */
    public function setMultiplier(float $multiplier): NthPercentOffRule
    {
        $this->multiplier = $multiplier;
        return $this;
    }
}
