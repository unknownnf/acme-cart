<?php

namespace Acme\Sales\Repository;

use ArrayAccess;
use ArrayIterator;

/**
 * Generic Repository class
 *
 * @package Acme\Sales\Repository
 */
class Repository implements ArrayAccess, \IteratorAggregate
{
    protected array $items = [];

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     * @param null  $key
     *
     * @return Repository
     */
    public function add(mixed $item, $key = null): static
    {
        $this->items[$key] = $item;
        return $this;

    }

    /**
     * @param $key
     *
     * @return Repository
     */
    public function delete($key): Repository
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key): mixed
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return count($this->items);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function keyExists($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}