<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Domain\Foundation\Service\CurrencyExchangePriceClientInterface;
use CurrencyConverter\Domain\Model\Currency\Currency;

class CurrencyExchangePriceService
{

    /**
     * @var CurrencyExchangePriceClientInterface
     */
    private $exchange_price_client;

    /**
     * CurrencyExchangePriceService constructor.
     * @param CurrencyExchangePriceClientInterface $exchange_price_client
     */
    public function __construct(CurrencyExchangePriceClientInterface $exchange_price_client)
    {
        $this->exchange_price_client = $exchange_price_client;
    }


    public function getExchangePrice(Currency $base_currency, Currency $destination_currency) : float
    {
        return $this->exchange_price_client->getExchangePrice($base_currency, $destination_currency);
    }
}