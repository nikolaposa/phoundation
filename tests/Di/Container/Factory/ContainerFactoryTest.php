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
use Phoundation\Di\Container\Factory\FactoryInterface;
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
     * @var FactoryInterface
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = $this->createFactory();
    }

    abstract protected function createFactory();

    /**
     * @test
     */
    public function it_registers_factories_from_config()
    {
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
        $container = $this->factory->__invoke($config);

        $this->assertInstanceOf(\PDO::class, $container->get('pdo'));
    }

    /**
     * @test
     */
    public function it_registers_factory_names_from_config()
    {
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
        $container = $this->factory->__invoke($config);

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }

    /**
     * @test
     */
    public function it_registers_invokables_from_config()
    {
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
        $container = $this->factory->__invoke($config);

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }

    /**
     * @test
     */
    public function it_puts_config_into_container_under_default_name()
    {
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
        $container = $this->factory->__invoke($config);

        $this->assertSame($config, $container->get(FactoryInterface::DEFAULT_CONFIG_SERVICE_NAME));
    }

    /**
     * @test
     */
    public function it_puts_config_into_container_under_specified_name()
    {
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
        $container = $this->factory->__invoke($config, FactoryInterface::DEFAULT_DI_CONFIG_KEY, 'AppConfig');

        $this->assertSame($config, $container->get('AppConfig'));
    }
}
