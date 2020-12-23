<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use CurrencyConverter\Application\Service\Currency\CurrConvClient;
use CurrencyConverter\Application\Service\Currency\CurrencyConverterService;
use CurrencyConverter\Application\Service\Currency\CurrencyExchangePriceService;
use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;
use DI\ContainerBuilder;;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Container\ContainerInterface;

class ServiceProvider implements ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container_builder) : void
    {
        $container_builder->addDefinitions([
            CurrencyExchangePriceService::class => function (ContainerInterface $container) {
                $client_params = $container->get('config')['currconv'];
                return new CurrencyExchangePriceService(
                    new CurrConvClient(new GuzzleClient(), $client_params['base_url'], $client_params['api_key'])
                );
            },
        ]);
        $container_builder->addDefinitions([
            CurrencyConverterService::class => function (ContainerInterface $container) {
                return new CurrencyConverterService(
                    $container->get(CurrencyRepository::class),
                    $container->get(CurrencyExchangePriceService::class)
                );
            },
        ]);

        $container_builder->addDefinitions([
            CurrencyCalculatorService::class => function (ContainerInterface $container) {
                return new CurrencyCalculatorService(
                    $container->get(CurrencyRepository::class),
                    $container->get(CurrencyConverterService::class)
                );
            },
        ]);
    }
}
