<?php

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyId;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use CurrencyConverter\Domain\Model\Currency\Money;
use CurrencyConverter\Domain\Model\Currency\MoneyCollection;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Connection;

class CurrencyConverterServiceTest extends TestCase
{
    private $repository = null;
    private $exchange_price_service = null;
    private $currency_converter_service = null;

    protected function setUp() : void
    {
        $this->repository = Mockery::mock(CurrencyRepository::class)->makePartial();
        $this->exchange_price_service = Mockery::mock(CurrencyExchangePriceService::class)->makePartial();
        $this->currency_converter_service = new CurrencyConverterService($this->repository, $this->exchange_price_service);
    }

    private function mockRepositoryToReturn()
    {
        $currencies = [
            'USD' => new Currency(new CurrencyId(1), "US Dollars", "US Dollars", new CurrencyIsoCode("USD"), "840"),
            'ARS' => new Currency(new CurrencyId(1), "Argentine peso", "Argentine peso", new CurrencyIsoCode("ARS"), "32"),
            'EUR' => new Currency(new CurrencyId(3), "Euro", "Euro", new CurrencyIsoCode("EUR"), "978"),
            'GBP' => new Currency(new CurrencyId(4), "Pound sterling", "Pound sterling", new CurrencyIsoCode("GBP"), "826"),
        ];

        $this->repository
            ->shouldReceive('getCurrencyByIsoCode')
            ->andReturnUsing(function ($iso_code) use ($currencies){
                return $currencies[$iso_code->getValue()];
            });
    }

    private function mockGetExchangePrice(float $exchange_price)
    {
        $this->exchange_price_service->shouldReceive('getExchangePrice')->andReturn($exchange_price);
    }

    public function testSuccessfullyConvertOne()
    {
        $exchange_price = 150.0;
        $amount = 10.0;

        $this->mockRepositoryToReturn();

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

        $this->mockRepositoryToReturn();

        $this->mockGetExchangePrice($exchange_price);

        $currency_code_from = new CurrencyIsoCode('USD');
        $amount = new CurrencyAmount($amount);
        $currencies_code_to =[
            new CurrencyIsoCode('ARS'),
            new CurrencyIsoCode('GBP')
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

        $this->mockRepositoryToReturn();

        $this->mockGetExchangePrice($exchange_price);

        $currency_code_from = new CurrencyIsoCode('USD');
        $currency_from = $this->repository->getCurrencyByIsoCode($currency_code_from);
        $amount = new CurrencyAmount($amount);
        $money = new Money($currency_from, $amount);

        $currency_code_to = new CurrencyIsoCode('ARS');
        $currency_to = $this->repository->getCurrencyByIsoCode($currency_code_to);


        $money = $this->currency_converter_service->convert($money, $currency_to);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals($exchange_price * $amount->getValue(), $money->getAmount()->getValue());
    }
}
