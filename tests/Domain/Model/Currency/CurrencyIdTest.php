<?php

namespace CurrencyConverter\Domain\Model\Currency;

use CurrencyConverter\Application\Exception\InvalidArgumentValueException;
use CurrencyConverter\Application\Exception\MissingArgumentException;
use PHPUnit\Framework\TestCase;

class CurrencyIdTest extends TestCase
{
    public function testCreateFromInt()
    {
        $this->create(1);
    }

    public function testMissing()
    {
        $this->expectException(MissingArgumentException::class);
        new CurrencyId('');
    }

    public function testsNull()
    {
        $this->expectException(MissingArgumentException::class);
        new CurrencyId(null);
    }

    public function testsInvalidType()
    {
        $this->expectException(InvalidArgumentValueException::class);
        new CurrencyId('string value');
    }

    private function create($value)
    {
        $obj = new CurrencyId($value);

        $this->assertInstanceOf(CurrencyId::class, $obj);
        $this->assertEquals($value, $obj->getId());
    }
}
