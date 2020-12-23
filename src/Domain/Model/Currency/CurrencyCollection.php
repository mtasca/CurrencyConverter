<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Domain\Foundation\Entity\EntityCollection;
use CurrencyConverter\Domain\Foundation\Entity\EntityInterface;

class CurrencyCollection extends EntityCollection
{
    public function addEntity(EntityInterface $entity) : void
    {
        if(!($entity instanceof Currency)){
            throw new InvalidArgumentException();
        }
        parent::addEntity($entity);
    }
}