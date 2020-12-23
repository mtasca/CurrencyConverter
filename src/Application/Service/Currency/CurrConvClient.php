<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Application\Exception\CurrencyExchangePriceClientException;
use CurrencyConverter\Domain\Foundation\Service\CurrencyExchangePriceClientInterface;
use CurrencyConverter\Domain\Model\Currency\Currency;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class CurrConvClient implements CurrencyExchangePriceClientInterface
{
    const CONVERT_ENDPOINT = 'convert';

    /**
     * @var GuzzleClient
     */
    private $guzzle_client;

    /**
     * @var string
     */
    private $base_url;

    /**
     * @var string
     */
    private $api_key;

    /**
     * CurrConvClient constructor.
     * @param GuzzleClient $guzzle_client
     * @param string $base_url
     * @param string $api_key
     */
    public function __construct(GuzzleClient $guzzle_client, string $base_url, string $api_key)
    {
        $this->guzzle_client = $guzzle_client;
        $this->base_url = $base_url;
        $this->api_key = $api_key;
    }


    public function getExchangePrice(Currency $base_currency, Currency $destination_currency): float
    {
        $exchange_currencies = sprintf(
            "%s_%s",
            $base_currency->getIsoCode()->getValue(),
            $destination_currency->getIsoCode()->getValue()
        );
        $url = sprintf(
            "%s%s?q=%s&compact=ultra&apiKey=%s",
            $this->base_url,
            self::CONVERT_ENDPOINT,
            $exchange_currencies,
            $this->api_key
        );

        try{
            $response = $this->guzzle_client->get($url);
            $response = json_decode($response->getBody()->getContents(),true);
            return $response[$exchange_currencies];
        } catch (GuzzleException $e) {
            throw new CurrencyExchangePriceClientException("Curr Conv Client exception", $e->getCode(), $e);
        }
    }
}