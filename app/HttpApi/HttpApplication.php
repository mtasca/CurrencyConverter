<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi;

use CurrencyConverter\HttpApi\Providers\ConfigProvider;
use CurrencyConverter\HttpApi\Providers\LoggerProvider;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use CurrencyConverter\Application\Handlers\HttpErrorHandler;
use CurrencyConverter\Application\Handlers\ShutdownHandler;
use CurrencyConverter\Domain\Model\Environment\Environment;

class HttpApplication
{
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * @var Environment
     */
    private $env;

    /**
     * @var \DI\Container
     */
    private $container_builder;

    /**
     * @var HttpErrorHandler
     */
    private $error_handler;

    /**
     * @var ShutdownHandler
     */
    private $shutdown_handler;

    public function __construct()
    {
        $this->env = new Environment($_ENV['APP_ENV']);
        // Instantiate PHP-DI ContainerBuilder
        $this->container_builder = new ContainerBuilder();

        if ($this->env->isProduction()) { // Should be set to true in production
            $this->container_builder->enableCompilation(__DIR__ . '/../var/cache');
        }

        $this->addProviders();

        // Build PHP-DI Container instance
        $this->container_builder = $this->container_builder->build();

        // Instantiate the app
        AppFactory::setContainer($this->container_builder);
        $this->app = AppFactory::create();

        // Add Routing Middleware
        $this->app->addRoutingMiddleware();

        /** @var bool $displayErrorDetails */
        $displayErrorDetails = $this->container_builder->get('config')['app']['displayErrorDetails'];

        $error_middleware = $this->app->addErrorMiddleware($displayErrorDetails,true,true);
        // Register routes
        $this->registerRoutes();

        // Create Request object from globals
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        // Create Error Handler
        $this->error_handler = new HttpErrorHandler(
            $this->env,
            $this->app->getCallableResolver(),
            $this->app->getResponseFactory(),
            $this->container_builder->get(LoggerInterface::class)
        );

        // Create Shutdown Handler
        $this->shutdown_handler = new ShutdownHandler($request, $this->error_handler, $displayErrorDetails);
        register_shutdown_function($this->shutdown_handler);

        $error_middleware->setDefaultErrorHandler($this->error_handler);

        // Run App & Emit Response
        $response = $this->app->handle($request);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }

    private function addProviders() : void
    {
        ConfigProvider::addDefinition($this->container_builder);
        LoggerProvider::addDefinition($this->container_builder);
    }

    private function registerRoutes()
    {
        $this->app->get(
            '/service/health',
            Controller\ServiceController::class . ':health'
        );
    }
}