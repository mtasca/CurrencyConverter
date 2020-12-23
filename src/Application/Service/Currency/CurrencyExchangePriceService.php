<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Domain\Model\Currency\Currency;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;

class CurrencyExchangePriceService
{

    public function getExchangePrice(Currency $base_currency, Currency $destination_currency) : float
    {
        return rand(0,1000)/500;
    }
}