<?php

declare(strict_types=1);

namespace Phoundation\Tests\DependencyInjection;

use Phoundation\DependencyInjection\DiContainerFactory;
use Phoundation\DependencyInjection\LaminasServiceManagerFactory;
use Phoundation\Tests\TestAsset\Service\LoggerFactory;
use Psr\Log\LoggerInterface;

class LaminasServiceManagerFactoryTest extends DiContainerFactoryTest
{
    protected function createFactory(): DiContainerFactory
    {
        return new LaminasServiceManagerFactory();
    }

    /**
     * @test
     */
    public function it_configures_dependencies(): void
    {
        $config = [
            'dependencies' => [
                'factories' => [
                    LoggerInterface::class => LoggerFactory::class,
                ],
            ],
        ];

        $container = $this->factory->create($config);

        $this->assertInstanceOf(LoggerInterface::class, $container->get(LoggerInterface::class));
    }

    /**
     * @test
     */
    public function it_sets_config_as_a_service(): void
    {
        $config = [
            'db' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'secret',
                'dbname' => 'test',
            ],
        ];

        $container = $this->factory->create($config);

        $this->assertSame($config, $container->get('config'));
    }
}
