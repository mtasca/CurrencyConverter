<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use CurrencyConverter\Application\Service\Currency\CurrencyConverterService;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;
use DI\ContainerBuilder;;
use Psr\Container\ContainerInterface;

class ServiceProvider implements ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container_builder) : void
    {
        $container_builder->addDefinitions([
            CurrencyExchangePriceService::class => function (ContainerInterface $container) {
                return new CurrencyExchangePriceService();
            },
        ]);
        $container_builder->addDefinitions([
            CurrencyConverterService::class => function (ContainerInterface $container) {
                return new CurrencyConverterService(
                    $container->get(CurrencyRepository::class),
                    $container->get(CurrencyExchangePriceService::class)
                );
            },
            CurrencyCalculatorService::class => function (ContainerInterface $container) {
                return new CurrencyCalculatorService(
                    $container->get(CurrencyRepository::class),
                    $container->get(CurrencyExchangePriceService::class)
                );
            },
        ]);
    }
}
