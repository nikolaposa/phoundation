<?php

return [
    'db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'secret',
        'dbname' => 'test',
    ],
    'di' => [
        'factories' => [
            'pdo' => function () {
                return new \PDO('sqlite::memory:');
            },
        ],
        'invokables' => [
            'logger' => \Phoundation\Tests\TestAsset\Service\InMemoryLogger::class,
        ],
    ],
    'foo' => [
        'bar',
    ],
];
