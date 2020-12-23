<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;


use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyCollection;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use CurrencyConverter\Domain\Model\Currency\Money;
use CurrencyConverter\Domain\Model\Currency\MoneyCollection;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;

class CurrencyConverterService
{

    /**
     * @var CurrencyRepository
     */
    private $currency_repository;

    /**
     * @var CurrencyExchangePriceService
     */
    private $exchange_price_service;


    public function __construct(CurrencyRepository $currency_repository, CurrencyExchangePriceService $exchange_price_service)
    {
        $this->currency_repository = $currency_repository;
        $this->exchange_price_service = $exchange_price_service;
    }

    public function convertOne(CurrencyIsoCode $from, CurrencyAmount $amount, CurrencyIsoCode $code_to) :Money
    {
        $currency_from = $this->currency_repository->getCurrencyByIsoCode($from);
        $money_from = new Money($currency_from, $amount);
        $currency_to = $this->currency_repository->getCurrencyByIsoCode($code_to);

        return $this->convert($money_from, $currency_to);
    }

    public function convertBulk(CurrencyIsoCode $from, CurrencyAmount $amount, CurrencyIsoCode ...$codes_to) :MoneyCollection
    {
        $currency_from = $this->currency_repository->getCurrencyByIsoCode($from);

        $money_from = new Money($currency_from, $amount);

        $currencies_to = new CurrencyCollection();
        foreach ($codes_to as $code) {
            $currencies_to->addEntity($this->currency_repository->getCurrencyByIsoCode($code));
        }

        $money_collection = new MoneyCollection();

        foreach ($currencies_to as $currency_to)
        {
            $money_collection->addItem(
                $this->convert($money_from, $currency_to)
            );
        }

        return $money_collection;
    }

    public function convert(Money $from, Currency $currency_to) : Money
    {
        $exchange_price = $this->exchange_price_service->getExchangePrice($from->getCurrency(), $currency_to);
        return new Money(
            $currency_to,
            new CurrencyAmount($from->getAmount()->getValue() * $exchange_price)
        );
    }
}