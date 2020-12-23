<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Controller;

use CurrencyConverter\Application\Response\ApiResponse;
use CurrencyConverter\Application\Response\BadRequestResponse;
use CurrencyConverter\Application\Service\Currency\CurrencyCalculatorService;
use CurrencyConverter\Application\Service\Currency\CurrencyConverterService;
use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use CurrencyConverter\Domain\Model\Currency\MoneyCollection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurrencyCalculatorController extends ApiController
{

    public function sum(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body_request = json_decode($request->getBody()->getContents(), true);

        if(empty($body_request['money'])
            || !is_array($body_request['money'])
            || count($body_request['money']) == 0
            || empty($body_request['destination'])
        ) {
            return new BadRequestResponse(['message' => 'Invalid body request, please check the docs']);
        }

        $money_collection = [];
        foreach ($body_request['money'] as $money) {
            $money_collection[] = [
                'code' => new CurrencyIsoCode($money['code']),
                'amount' => new CurrencyAmount($money['amount'])
            ];
        }

        $currency_code_to = new CurrencyIsoCode($body_request['destination']);

        $currency_converter_service = $this->container->get(CurrencyCalculatorService::class);

        $money = $currency_converter_service->sum($money_collection, $currency_code_to);

        return new ApiResponse($money->toArray());
    }
}