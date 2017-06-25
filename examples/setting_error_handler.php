<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Phoundation\Bootstrap\Bootstrap;
use Phoundation\Config\Loader\StaticConfigLoader;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;
use Phoundation\ErrorHandling\Handler\LogHandler;
use Phoundation\ErrorHandling\RunnerInterface;
use Phoundation\ErrorHandling\WhoopsRunner;
use Psr\Container\ContainerInterface;
use Psr\Log\NullLogger;
use Whoops\Run;

$config = [
    'db' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.1',
        'user' => 'root',
        'password' => 'secret',
    ],
    'di' => [
        'factories' => [
            //automatically registered during bootstrap
            RunnerInterface::class => function () {
                $runner = new WhoopsRunner(new Run());
                $runner->pushHandler(new LogHandler(new NullLogger()));

                return $runner;
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

assert($diContainer->has(RunnerInterface::class));
