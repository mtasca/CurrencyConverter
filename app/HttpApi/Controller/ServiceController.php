<?php
declare(strict_types=1);

namespace CurrencyConverter\HttpApi\Controller;

use Psr\Http\Message\ResponseInterface;
use CurrencyConverter\Application\Response\ApiResponse;

class ServiceController extends ApiController
{
    public function health(): ResponseInterface
    {
        return new ApiResponse([
            "isHealthy" => true,
            "status" => 200,
            "env" => $this->container->get('config')['app']['env']
        ]);
    }
}
