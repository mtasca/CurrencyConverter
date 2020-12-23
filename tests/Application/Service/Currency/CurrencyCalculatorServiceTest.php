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

class CurrencyCalculatorServiceTest extends TestCase
{

    private $repository = null;
    private $exchange_price_service = null;
    private $currency_converter_service = null;
    private $currency_calculator = null;

    protected function setUp() : void
    {
        $this->repository = Mockery::mock(CurrencyRepository::class)->makePartial();;
        $this->exchange_price_service = Mockery::mock(CurrencyExchangePriceService::class)->makePartial();
        $this->currency_converter_service = new CurrencyConverterService($this->repository, $this->exchange_price_service);
        $this->currency_calculator = new CurrencyCalculatorService($this->repository, $this->currency_converter_service);
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

    public function testSuccessfullySum()
    {
        $exchange_price = 150;
        $amount = 10;

        $this->mockRepositoryToReturn();

        $this->mockGetExchangePrice($exchange_price);

        $money = [
            [
                'code' => new CurrencyIsoCode('USD'),
                'amount' => new CurrencyAmount($amount),
            ],
            [
                'code' => new CurrencyIsoCode('EUR'),
                'amount' => new CurrencyAmount($amount),
            ],
            [
                'code' => new CurrencyIsoCode('GBP'),
                'amount' => new CurrencyAmount($amount),
            ]
        ];

        $currency_code_to = new CurrencyIsoCode('ARS');

        $money = $this->currency_calculator->sum($money, $currency_code_to);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(
            ($amount*$exchange_price)+($amount*$exchange_price)+($amount*$exchange_price),
            $money->getAmount()->getValue()
        );
    }
}

