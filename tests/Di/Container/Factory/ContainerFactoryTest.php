<?php
/**
 * This file is part of the PHP App Foundation package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Foundation\Tests\Di\Container\Factory;

use PHPUnit\Framework\TestCase;
use Foundation\Di\Container\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Foundation\Config\Config;

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
