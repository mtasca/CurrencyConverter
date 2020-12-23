<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Domain\Foundation\Entity\EntityCollection;
use CurrencyConverter\Domain\Foundation\Entity\EntityInterface;

class MoneyCollection extends EntityCollection
{

    protected $money_collection;

    public function __construct()
    {
        $this->money_collection = [];
    }


    public function addItem(Money $money) : void
    {
        $this->money_collection[] = $money;
    }


    public function isEmpty() : bool
    {
        return empty($this->money_collection);
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->money_collection as $money) {
            $data[] = $money->toArray();
        }

        return $data;
    }
}