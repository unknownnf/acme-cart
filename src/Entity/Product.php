<?php

namespace Acme\Sales\Entity;

/**
 * Product class
 *
 * @package Acme\Sales\Entity
 */
class Product
{
    protected string $code;

    protected string $name;

    protected float $price;

    /**
     * Product constructor
     *
     * @param string $code
     * @param string $name
     * @param float  $price
     */
    public function __construct(string $code, string $name, float $price)
    {
        $this->setCode($code)
            ->setName($name)
            ->setPrice($price);
    }

    /**
     * Get product code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set product code
     *
     * @param string $code
     *
     * @return Product
     */
    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set product name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get product price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set product price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }


}