<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use CurrencyConverter\Infraestructure\MySql\CurrencyRepository;
use DI\ContainerBuilder;;

use Psr\Container\ContainerInterface;

class RepositoryProvider implements ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container_builder) : void
    {
        $container_builder->addDefinitions([
            'dbs.db_read' => function (ContainerInterface $container) {
                $connection_params = $container->get('config')['db']['dbs.options']['db_read'];
                return \Doctrine\DBAL\DriverManager::getConnection($connection_params);

            },
            'dbs.db_write' => function (ContainerInterface $container) {
                $connection_params = $container->get('config')['db']['dbs.options']['db_write'];
                return \Doctrine\DBAL\DriverManager::getConnection($connection_params);

            },
        ]);
        $container_builder->addDefinitions([
            CurrencyRepository::class => function (ContainerInterface $container) {
                return new CurrencyRepository(
                    $container->get('dbs.db_read'),
                    $container->get('dbs.db_write')
                );
            },
        ]);
    }
}
