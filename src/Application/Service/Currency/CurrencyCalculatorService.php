<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Domain\Model\Currency\CurrencyAmount;
use CurrencyConverter\Domain\Model\Currency\CurrencyIsoCode;
use CurrencyConverter\Domain\Model\Currency\Money;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;

class CurrencyCalculatorService
{
    /**
     * @var CurrencyRepository
     */
    private $currency_repository;

    /**
     * @var CurrencyConverterService
     */
    private $converter_service;


    public function __construct(CurrencyRepository $currency_repository, CurrencyConverterService $converter_service)
    {
        $this->currency_repository = $currency_repository;
        $this->converter_service = $converter_service;
    }

    public function sum(array $money, CurrencyIsoCode $code_to) :Money
    {
        $currency_to = $this->currency_repository->getCurrencyByIsoCode($code_to);

        $total_amount = 0;

        foreach ($money as $m) {
            $currency_from = $this->currency_repository->getCurrencyByIsoCode($m['code']);
            $money_from = new Money($currency_from, $m['amount']);
            $money = $this->converter_service->convert($money_from, $currency_to);
            $total_amount += $money->getAmount()->getValue();
        }

        return new Money($currency_to, new CurrencyAmount($total_amount));
    }
}
