<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use Carbon\Carbon;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerProvider implements ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container_builder) : void
    {
        $container_builder->addDefinitions([
            LoggerInterface::class => function (ContainerInterface $container) {
                $logger_settings = $container->get('config')['logger'];
                $logger = new Logger($logger_settings['name']);
                $processor = new UidProcessor();
                $logger->pushProcessor($processor);
                $hourly_file_path = sprintf(
                    '%s%s/%s_%s.log',
                    __DIR__,
                    $logger_settings['path'],
                    $logger_settings['filename'],
                    Carbon::now()->format('YmdH')
                );
                $handler = new StreamHandler($hourly_file_path, $logger_settings['level']);
                $logger->pushHandler($handler);

                return $logger;
            },
        ]);
    }

}