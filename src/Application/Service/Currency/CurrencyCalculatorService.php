<?php
declare(strict_types=1);

namespace CurrencyConverter\Application\Service\Currency;

use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;

class CurrencyCalculatorService
{
    /**
     * @var CurrencyRepository
     */
    private $currency_repository;


    public function __construct(CurrencyRepository $currency_repository)
    {
        $this->currency_repository = $currency_repository;
    }

}