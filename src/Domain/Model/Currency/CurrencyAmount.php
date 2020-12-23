<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use CurrencyConverter\Domain\Foundation\Entity\ValueObject;

final class CurrencyAmount extends ValueObject
{

    const DECIMAL_PRECISION = 2;

    public function __construct($value)
    {
        if (empty($value)) {
            throw new MissingArgumentException("Missing Currency Amount Value");
        }

        if (!is_numeric($value) && $value > 0) {
            throw new InvalidArgumentValueException("Invalid Currency Amount value");
        }

        $this->value = round((float)$value, self::DECIMAL_PRECISION);
    }

}