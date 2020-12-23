<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;


class MoneyCollection
{
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(Money $money) : void
    {
        $this->items[] = $money;
    }

    public function getItems() : array
    {
        return $this->items;
    }

    public function count() : int
    {
        return count($this->items);
    }

    public function isEmpty() : bool
    {
        return empty($this->items);
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->items as $money) {
            $data[] = $money->toArray();
        }

        return $data;
    }
}