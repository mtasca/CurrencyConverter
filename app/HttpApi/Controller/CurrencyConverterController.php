<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Controller;

use CurrencyConverter\Application\Response\ApiResponse;
use CurrencyConverter\Application\Response\BadRequestResponse;
use CurrencyConverter\Application\Service\Currency\CurrencyConverterService;
use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurrencyConverterController extends ApiController
{

    public function convertOne(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body_request = json_decode($request->getBody()->getContents(), true);

        if(empty($body_request['from']) || empty($body_request['amount']) || empty($body_request['to'])
        ) {
            return new BadRequestResponse(['message' => 'Invalid body request, please check the docs']);
        }

        $currency_code_from = new CurrencyIsoCode($body_request['from']);
        $currency_amount = new CurrencyAmount($body_request['amount']);
        $currency_code_to = new CurrencyIsoCode($body_request['to']);

        $currency_converter_service = $this->container->get(CurrencyConverterService::class);

        $money = $currency_converter_service->convertOne($currency_code_from, $currency_amount, $currency_code_to);

        return new ApiResponse($money->toArray());
    }

    public function convertBulk(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body_request = json_decode($request->getBody()->getContents(), true);

        if(empty($body_request['from'])
            || empty($body_request['amount'])
            || empty($body_request['to'])
            || !is_array($body_request['to'])
            || count($body_request['to']) == 0
        ) {
            return new BadRequestResponse(['message' => 'Invalid body request, please check the docs']);
        }

        $currency_code_from = new CurrencyIsoCode($body_request['from']);
        $currency_amount = new CurrencyAmount($body_request['amount']);

        if(is_array($body_request['to']) && count($body_request['to']) > 0)
        $currency_codes_to = [];
        foreach ($body_request['to'] as $currency_code) {
            $currency_codes_to[] = new CurrencyIsoCode($currency_code);
        }

        $currency_converter_service = $this->container->get(CurrencyConverterService::class);

        $money_collection = $currency_converter_service->convertBulk($currency_code_from, $currency_amount, ...$currency_codes_to);

        return new ApiResponse($money_collection->toArray());
    }
}