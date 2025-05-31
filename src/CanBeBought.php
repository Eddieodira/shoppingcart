<?php

declare(strict_types=1);

namespace Eddieodira\Shoppingcart;

trait CanBeBought
{
    /**
     * Get the identifier of buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier(): int|string
    {
        return method_exists($this, 'getKey')
            ? $this->getKey()
            : $this->id;
    }

    /**
     * Get the description or title of the buyable item.
     *
     * @param null $options
     * @return string
     */
    public function getBuyableDescriptions(?string $options = null): string
    {
        if (property_exists($this, 'name')) {
            return $this->name;
        }

        if (property_exists($this, 'title')) {
            return $this->title;
        }

        if (property_exists($this, 'description')) {
            return $this->description;
        }

        return null;
    }

    /**
     * Get the price of buyable item.
     *
     * @return float
     */
    public function getBuyablePrice(): float
    {
        return property_exists($this, 'price')
            ? $this->price
            : null;
    }
}
