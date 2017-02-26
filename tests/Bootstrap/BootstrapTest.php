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

namespace Phoundation\Bootstrap\Tests;

use PHPUnit\Framework\TestCase;
use Phoundation\Bootstrap\Bootstrap;
use Phoundation\Config\Loader\StaticConfigLoader;
use Interop\Container\ContainerInterface;
use Phoundation\Config\Config;
use Phoundation\Di\Container\Factory\DiContainerFactoryInterface;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;
use Zend\ServiceManager\ServiceManager;
use Phoundation\ErrorHandling\RunnerInterface;
use Phoundation\ErrorHandling\WhoopsRunner;
use Whoops\Run as Whoops;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class BootstrapTest extends TestCase
{
    /**
     * @test
     */
    public function it_produces_di_container()
    {
        $bootstrap = new Bootstrap(
            new StaticConfigLoader([
                'foo' => 'bar',
            ]),
            new ZendServiceManagerFactory()
        );

        $diContainer = $bootstrap();

        $this->assertInstanceOf(ContainerInterface::class, $diContainer);
        $this->assertInstanceOf(ServiceManager::class, $diContainer);
    }

    /**
     * @test
     */
    public function it_puts_config_into_di_container()
    {
        $bootstrap = new Bootstrap(
            new StaticConfigLoader([
                'foo' => 'bar',
            ]),
            new ZendServiceManagerFactory()
        );

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $config = $diContainer->get(DiContainerFactoryInterface::DEFAULT_CONFIG_SERVICE_NAME);
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_sets_php_settings_if_provided_through_config()
    {
        $currentTz = ini_get('date.timezone');

        $bootstrap = new Bootstrap(
            new StaticConfigLoader([
                'php_settings' => [
                    'date.timezone' => 'Europe/Belgrade',
                ],
            ]),
            new ZendServiceManagerFactory()
        );

        $bootstrap();

        $this->assertEquals('Europe/Belgrade', ini_get('date.timezone'));

        ini_set('date.timezone', $currentTz);
    }

    /**
     * @test
     */
    public function it_registers_error_handler_if_provided_through_di_config()
    {
        $bootstrap = new Bootstrap(
            new StaticConfigLoader([
                'di' => [
                    'factories' => [
                        RunnerInterface::class => function () {
                            return new WhoopsRunner(new Whoops());
                        },
                    ]
                ],
            ]),
            new ZendServiceManagerFactory()
        );

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $this->assertTrue($diContainer->has(RunnerInterface::class));

        $diContainer->get(RunnerInterface::class)->unregister();
    }
}
