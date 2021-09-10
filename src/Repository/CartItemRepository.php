<?php

namespace Acme\Sales\Repository;

use Acme\Sales\Entity\CartItem;

/**
 * Cart Item Repository class
 *
 * @package Acme\Sales\Repository
 */
class CartItemRepository extends Repository
{

    /**
     * @param mixed $item
     * @param null  $key
     *
     * @return CartItemRepository
     */
    public function add(mixed $item, $key = null): static
    {
        if ($item instanceof CartItem) {
            $this->items[$item->getProduct()->getCode()] = $item;
        }
        return $this;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($value instanceof CartItem) {
            if (is_null($offset)) {
                $this->items[] = $value;
            } else {
                $this->items[$offset] = $value;
            }
        }
    }
}
