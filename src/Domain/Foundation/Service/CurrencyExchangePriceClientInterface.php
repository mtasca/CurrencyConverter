<?php


namespace CurrencyConverter\Domain\Foundation\Service;

use CurrencyConverter\Domain\Model\Currency\Currency;

interface CurrencyExchangePriceClientInterface
{
    public function getExchangePrice(Currency $base_currency, Currency $destination_currency) : float;
}