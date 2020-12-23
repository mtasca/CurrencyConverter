<?php

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use CurrencyConverter\Domain\Model\Currency\Money;
use CurrencyConverter\Domain\Model\Currency\MoneyCollection;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Connection;

class CurrencyConverterServiceTest extends TestCase
{
    private $db_read = null;
    private $db_write = null;
    private $repository = null;
    private $exchange_price_service = null;
    private $currency_converter_service = null;

    protected function setUp() : void
    {
        $this->db_read    = Mockery::mock(Connection::class)->makePartial();
        $this->db_write   = Mockery::mock(Connection::class)->makePartial();
        $this->repository = new CurrencyRepository($this->db_read, $this->db_write);
        $this->exchange_price_service = Mockery::mock(CurrencyExchangePriceService::class)->makePartial();
        $this->currency_converter_service = new CurrencyConverterService($this->repository, $this->exchange_price_service);
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

    private function mockGetExchangePrice(float $exchange_price)
    {
        $this->exchange_price_service->shouldReceive('getExchangePrice')->andReturn($exchange_price);
    }

    public function testSuccessfullyConvertOne()
    {
        $exchange_price = 150.0;
        $amount = 10.0;
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

        $this->mockGetExchangePrice($exchange_price);

        $currency_code_from = new CurrencyIsoCode('USD');
        $amount = new CurrencyAmount($amount);
        $currency_code_to = new CurrencyIsoCode('ARS');

        $money = $this->currency_converter_service->convertOne($currency_code_from, $amount, $currency_code_to);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals($exchange_price * $amount->getValue(), $money->getAmount()->getValue());
    }

    public function testSuccessfullyConvertBulk()
    {
        $exchange_price = 150.0;
        $amount = 10.0;
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

        $this->mockGetExchangePrice($exchange_price);

        $currency_code_from = new CurrencyIsoCode('USD');
        $amount = new CurrencyAmount($amount);
        $currencies_code_to =[
            new CurrencyIsoCode('ARS'),
            new CurrencyIsoCode('GPB')
        ];

        $money_collection = $this->currency_converter_service->convertBulk($currency_code_from, $amount, ...$currencies_code_to);
        $items = $money_collection->getItems();
        $money_1 = $items[0];
        $money_2 = $items[1];

        $this->assertInstanceOf(MoneyCollection::class, $money_collection);
        $this->assertEquals(2, $money_collection->count());
        $this->assertEquals($exchange_price * $amount->getValue(), $money_1->getAmount()->getValue());
        $this->assertEquals($exchange_price * $amount->getValue(), $money_2->getAmount()->getValue());
    }

    public function testSuccessfullyConvert()
    {
        $exchange_price = 150.0;
        $amount = 10.0;
        $data_1 = [
            [
                "id"=>"1",
                "name"=>"US Dollars",
                "description"=>"US Dollars",
                "iso_code"=>"USD",
                "iso_number"=>"840",
            ]
        ];

        $data_2 = [
            [
                "id"=>"2",
                "name"=>"Argentine Pesos",
                "description"=>"Argentine Pesos",
                "iso_code"=>"ARS",
                "iso_number"=>"1",
            ]
        ];

        $this->mockDatabaseToReturn($data_1);

        $this->mockGetExchangePrice($exchange_price);

        $currency_code_from = new CurrencyIsoCode('USD');
        $this->mockDatabaseToReturn($data_1);
        $currency_from = $this->repository->getCurrencyByIsoCode($currency_code_from);
        $amount = new CurrencyAmount($amount);
        $money = new Money($currency_from, $amount);

        $currency_code_to = new CurrencyIsoCode('ARS');
        $this->mockDatabaseToReturn($data_2);
        $currency_to = $this->repository->getCurrencyByIsoCode($currency_code_to);


        $money = $this->currency_converter_service->convert($money, $currency_to);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals($exchange_price * $amount->getValue(), $money->getAmount()->getValue());
    }
}
