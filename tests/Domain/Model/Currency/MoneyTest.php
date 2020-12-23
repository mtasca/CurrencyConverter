<?php

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testCreate()
    {
        $id = 1;
        $name = "US Dollars";
        $description = "US Dollars";
        $iso_code = "USD";
        $iso_number = 840;

        $amount = 10;

        $currency = new Currency(new CurrencyId($id), $name, $description, new CurrencyIsoCode($iso_code), $iso_number);

        $money = new Money($currency, new CurrencyAmount($amount));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertInstanceOf(Currency::class, $money->getCurrency());
        $this->assertInstanceOf(CurrencyId::class, $money->getCurrency()->getEntityId());
        $this->assertEquals($id, $money->getCurrency()->getId());
        $this->assertEquals($name, $money->getCurrency()->getName());
        $this->assertEquals($description, $money->getCurrency()->getDescription());
        $this->assertEquals($iso_code, $money->getCurrency()->getIsoCode()->getValue());
        $this->assertInstanceOf(CurrencyIsoCode::class, $money->getCurrency()->getIsoCode());
        $this->assertEquals($iso_number, $money->getCurrency()->getIsoNumber());
        $this->assertInstanceOf(CurrencyAmount::class, $money->getAmount());
        $this->assertEquals($amount, $money->getAmount()->getValue());
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
