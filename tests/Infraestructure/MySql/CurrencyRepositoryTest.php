<?php

namespace CurrencyConverter\Infraestructure\MySql;

use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use Mockery;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Connection;

class CurrencyRepositoryTest extends TestCase
{
    private $db_read = null;
    private $db_write = null;
    private $repository = null;

    protected function setUp() : void
    {
        $this->db_read    = Mockery::mock(Connection::class)->makePartial();
        $this->db_write   = Mockery::mock(Connection::class)->makePartial();
        $this->repository = new CurrencyRepository($this->db_read, $this->db_write);
    }

    private function mockDatabaseToReturn(array $data)
    {
        $this->db_read
            ->shouldReceive('connect')->andReturn($this->db_read)
            ->shouldReceive('createQueryBuilder')->once()->andReturn($this->db_read)
            ->shouldReceive('select')->once()->andReturn($this->db_read)
            ->shouldReceive('from')->once()->andReturn($this->db_read)
            ->shouldReceive('innerJoin')->once()->andReturn($this->db_read)
            ->shouldReceive('where')->once()->andReturn($this->db_read)
            ->shouldReceive('setParameter')->once()->andReturn($this->db_read)
            ->shouldReceive('andWhere')->once()->andReturn($this->db_read)
            ->shouldReceive('setParameter')->once()->andReturn($this->db_read)
            ->shouldReceive('setMaxResults')->once()->andReturn($this->db_read)
            ->shouldReceive('expr')->once()->andReturn($this->db_read)
            ->shouldReceive('quote')->once()->andReturn($this->db_read)
            ->shouldReceive('in')->once()->andReturn($this->db_read)
            ->shouldReceive('orderBy')->once()->andReturn($this->db_read)
            ->shouldReceive('execute')->once()->andReturn($this->db_read)
            ->shouldReceive('fetchAll')->once()->andReturn($data);
    }

    public function testSuccessfullyReturnCurrencyByIsoCode()
    {
        $data = [
            [
                "id"=>"1",
                "name"=>"US Dollars",
                "description"=>"US Dollars",
                "iso_code"=>"USD",
                "iso_number"=>"840",
            ]
        ];

        $this->mockDatabaseToReturn($data);

        $currency_iso_code = new CurrencyIsoCode('USD');

        $currency = $this->repository->getCurrencyByIsoCode($currency_iso_code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($data[0]["id"], $currency->getId());
        $this->assertEquals($data[0]["name"], $currency->getName());
        $this->assertEquals($data[0]["description"], $currency->getDescription());
        $this->assertEquals($data[0]["iso_code"], $currency->getIsoCode()->getValue());
        $this->assertEquals($data[0]["iso_number"], $currency->getIsoNumber());
    }
}
