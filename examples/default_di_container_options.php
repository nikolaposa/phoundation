<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Phoundation\Bootstrap\Bootstrap;
use Phoundation\Config\Loader\StaticConfigLoader;
use Phoundation\Di\Container\Factory\DiContainerFactoryInterface;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;
use Psr\Container\ContainerInterface;

$config = [
    'db' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.1',
        'user' => 'root',
        'password' => 'secret',
    ],
    'di' => [
        'factories' => [
            'DbConnection' => function (ContainerInterface $container) {
                $config = $container->get('config');
                $dbConfig = $config['db'];

                return new PDO($dbConfig['dsn'], $dbConfig['user'], $dbConfig['password']);
            },
        ]
    ],
];

$bootstrap = new Bootstrap(
    new StaticConfigLoader($config),
    new ZendServiceManagerFactory()
);
/* @var $diContainer ContainerInterface */
$diContainer = $bootstrap();

assert($diContainer->has(DiContainerFactoryInterface::DEFAULT_CONFIG_SERVICE_NAME));
assert($diContainer->has('DbConnection'));
