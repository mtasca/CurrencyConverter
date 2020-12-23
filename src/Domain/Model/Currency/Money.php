<?php

declare(strict_types=1);

namespace CurrencyConverter\Domain\Model\Currency;


class Money
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var CurrencyAmount
     */
    private $amount;

    public function __construct(Currency $currency, CurrencyAmount $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return CurrencyAmount
     */
    public function getAmount(): CurrencyAmount
    {
        return $this->amount;
    }

    /**
     * @param CurrencyAmount $amount
     */
    public function setAmount(CurrencyAmount $amount): void
    {
        $this->amount = $amount;
    }

    public function toArray() : array {
        return [
            'currency' => $this->currency->toArray(),
            'amount' => $this->amount->getValue(),
        ];
    }


}