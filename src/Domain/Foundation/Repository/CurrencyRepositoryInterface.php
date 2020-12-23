<?php


namespace CurrencyConverter\Domain\Foundation\Repository;


use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;

interface CurrencyRepositoryInterface
{
    public function getCurrencyByIsoCode(CurrencyIsoCode $iso_code) : Currency;
}