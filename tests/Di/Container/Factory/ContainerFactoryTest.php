<?php
/**
 * This file is part of the Phoundation package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Phoundation\Tests\Di\Container\Factory;

use PHPUnit\Framework\TestCase;
use Phoundation\Di\Container\Factory\DiContainerFactoryInterface;
use Psr\Container\ContainerInterface;
use Phoundation\Config\Config;
use Phoundation\Tests\TestAsset\Service\InMemoryLogger;
use Phoundation\Tests\TestAsset\Service\InMemoryLoggerFactory;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class ContainerFactoryTest extends TestCase
{
    /**
     * @var DiContainerFactoryInterface
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = $this->createFactory();
    }

    abstract protected function createFactory(array $options = []);

    /**
     * @test
     */
    public function it_registers_factories_from_config()
    {
        $factory = $this->createFactory();
        $config = Config::fromArray([
            'di' => [
                'factories' => [
                    'pdo' => function () {
                        return new \PDO('sqlite::memory:');
                    },
                ],
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertInstanceOf(\PDO::class, $container->get('pdo'));
    }

    /**
     * @test
     */
    public function it_registers_factory_names_from_config()
    {
        $factory = $this->createFactory();
        $config = Config::fromArray([
            'di' => [
                'factories' => [
                    'logger' => InMemoryLoggerFactory::class,
                ],
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }

    /**
     * @test
     */
    public function it_registers_invokables_from_config()
    {
        $factory = $this->createFactory();
        $config = Config::fromArray([
            'di' => [
                'invokables' => [
                    'logger' => InMemoryLogger::class,
                ],
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }

    /**
     * @test
     */
    public function it_registers_services_from_custom_config_key()
    {
        $factory = $this->createFactory([
            'di_config_key' => 'dependencies',
        ]);
        $config = Config::fromArray([
            'dependencies' => [
                'factories' => [
                    'logger' => InMemoryLoggerFactory::class,
                ],
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }

    /**
     * @test
     */
    public function it_sets_config_into_container_under_default_name()
    {
        $factory = $this->createFactory();
        $config = Config::fromArray([
            'db' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'secret',
                'dbname' => 'test',
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertSame($config, $container->get(DiContainerFactoryInterface::DEFAULT_CONFIG_SERVICE_NAME));
    }

    /**
     * @test
     */
    public function it_sets_config_into_container_under_custom_name()
    {
        $factory = $this->createFactory([
            'config_service_name' => 'AppConfig',
        ]);
        $config = Config::fromArray([
            'db' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'secret',
                'dbname' => 'test',
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $this->assertSame($config, $container->get('AppConfig'));
    }
}
