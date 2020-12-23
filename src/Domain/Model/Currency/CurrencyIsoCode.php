<?php

declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use CurrencyConverter\Domain\Foundation\Entity\ValueObject;

final class CurrencyIsoCode extends ValueObject
{
    const MAX_CHARACTERS = 3;

    public function __construct($value)
    {
        if (empty($value)) {
            throw new MissingArgumentException();
        }

        if (!is_string($value) || strlen($value) > self::MAX_CHARACTERS) {
            throw new InvalidArgumentValueException();
        }

        $this->value = $value;
    }

}