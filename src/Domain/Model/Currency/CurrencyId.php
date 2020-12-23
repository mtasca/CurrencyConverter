<?php

declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Domain\Foundation\Entity\EntityId;
use CurrencyConverter\Domain\Model\EntityType;

class CurrencyId extends EntityId
{

    protected function isValid($id): bool
    {
        return is_int($id) && $id > 0;
    }

    public function getType(): string
    {
        return EntityType::CURRENCY;
    }
}