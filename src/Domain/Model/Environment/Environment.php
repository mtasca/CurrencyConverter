<?php
declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Environment;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use CurrencyConverter\Domain\Foundation\Entity\ValueObject;

final class Environment extends ValueObject
{
    const DEV  = 'dev';
    const PROD = 'prod';
    const TEST = 'test';

    public function __construct($value)
    {
        if(empty($value)) {
            throw new MissingArgumentException();
        }

        if(!in_array($value, $this->getValidEnvironments())){
            throw new InvalidArgumentValueException();
        }

        $this->value = $value;
    }

    private function getValidEnvironments()
    {
        return [
            self::DEV,
            self::PROD,
            self::TEST,
        ];
    }

    public function isProduction()
    {
        return $this->getValue() === self::PROD;
    }

    public function isDevelopment()
    {
        return $this->getValue() === self::DEV;
    }

    public function isTest()
    {
        return $this->getValue() === self::TEST;
    }

}