<?php

namespace Acme\Sales\Repository;

use Acme\Sales\Entity\Product;

/**
 * Product Repository class
 *
 * @package Acme\Sales\Repository
 */
class ProductRepository extends Repository
{

    /**
     * @param mixed $item
     * @param       $key
     *
     * @return ProductRepository
     */
    public function add(mixed $item, $key = null): static
    {
        if ($item instanceof Product) {
            $this->items[$item->getCode()] = $item;
        }
        return $this;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($value instanceof Product) {
            if (is_null($offset)) {
                $this->items[] = $value;
            } else {
                $this->items[$offset] = $value;
            }
        }
    }
}
