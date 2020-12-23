<?php

return [
    'dbs.options' => [
        'db_read' => [
            'driver' => 'pdo_mysql',
            'host' => isset($_ENV['DB_READ_HOST']) ? $_ENV['DB_READ_HOST'] : null,
            'dbname' => isset($_ENV['DB_READ_DBNAME']) ? $_ENV['DB_READ_DBNAME'] : null,
            'user' => isset($_ENV['DB_READ_USER']) ? $_ENV['DB_READ_USER'] : null,
            'password' => isset($_ENV['DB_READ_PASSWORD']) ? $_ENV['DB_READ_PASSWORD'] : null,
            'charset' => 'utf8mb4',
        ],
        'db_write' => [
            'driver' => 'pdo_mysql',
            'host' => isset($_ENV['DB_WRITE_HOST']) ? $_ENV['DB_WRITE_HOST'] : null,
            'dbname' => isset($_ENV['DB_WRITE_DBNAME']) ? $_ENV['DB_WRITE_DBNAME'] : null,
            'user' => isset($_ENV['DB_WRITE_USER']) ? $_ENV['DB_WRITE_USER'] : null,
            'password' => isset($_ENV['DB_WRITE_PASSWORD']) ? $_ENV['DB_WRITE_PASSWORD'] : null,
            'charset' => 'utf8mb4',
        ],
    ],
];
