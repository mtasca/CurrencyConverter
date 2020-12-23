<?php

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testCreate()
    {
        $id = 1;
        $name = "US Dollars";
        $description = "US Dollars";
        $iso_code = "USD";
        $iso_number = 840;
        $currency = new Currency(new CurrencyId($id), $name, $description, new CurrencyIsoCode($iso_code), $iso_number);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertInstanceOf(CurrencyId::class, $currency->getEntityId());
        $this->assertEquals($id, $currency->getId());
        $this->assertEquals($name, $currency->getName());
        $this->assertEquals($description, $currency->getDescription());
        $this->assertEquals($iso_code, $currency->getIsoCode()->getValue());
        $this->assertInstanceOf(CurrencyIsoCode::class, $currency->getIsoCode());
        $this->assertEquals($iso_number, $currency->getIsoNumber());
    }

    public function testCreateWithInvalidId()
    {
        $this->expectException(InvalidArgumentValueException::class);

        $id = "1";
        $name = "US Dollars";
        $description = "US Dollars";
        $iso_code = "USD";
        $iso_number = 840;

        $currency = new Currency(new CurrencyId($id), $name, $description, new CurrencyIsoCode($iso_code), $iso_number);
    }

}
