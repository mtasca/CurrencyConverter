<?php
declare(strict_types=1);
return [
    "base_url" => isset($_ENV['CURRCONV_BASE_URL']) ? $_ENV['CURRCONV_BASE_URL'] : null,
    "api_key" => isset($_ENV['CURRCONV_API_KEY']) ? $_ENV['CURRCONV_API_KEY'] : null,
];