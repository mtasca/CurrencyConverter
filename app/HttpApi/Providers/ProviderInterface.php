<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use DI\ContainerBuilder;

interface ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container) : void;
}
