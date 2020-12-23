<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Providers;

use DI\ContainerBuilder;

class ConfigProvider implements ProviderInterface
{
    public static function addDefinition(ContainerBuilder $container_builder) : void
    {
        $config_path = __DIR__ . "/../../config";
        $config_files = scandir($config_path);

        $config = [];
        foreach ($config_files as $filename) {
            if (in_array($filename,array(".",".."))){
                continue;
            }
            $config_name = pathinfo($filename, PATHINFO_FILENAME);
            $config_array = require_once(sprintf("%s/%s", $config_path, $filename));
            $config[$config_name] = $config_array;
        }
        $container_builder->addDefinitions([
            'config' => $config
        ]);
    }
}
