<?php declare(strict_types=1);

namespace Phoundation\Tests\TestAsset\Config;

use PDO;
use Phoundation\Tests\TestAsset\Service\InMemoryLogger;

return [
    'db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'secret',
        'dbname' => 'test',
    ],
    'dependencies' => [
        'factories' => [
            'pdo' => function () {
                return new PDO('sqlite::memory:');
            },
        ],
        'invokables' => [
            'logger' => InMemoryLogger::class,
        ],
    ],
    'foo' => [
        'bar',
    ],
];
