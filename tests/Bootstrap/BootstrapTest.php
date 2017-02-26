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
use Interop\Container\ContainerInterface;
use Phoundation\Config\Config;
use Phoundation\Di\Container\Factory\FactoryInterface;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;
use Zend\ServiceManager\ServiceManager;
use Phoundation\Exception\InvalidBootstrapOptionsException;
use Phoundation\ErrorHandling\RunnerInterface;
use Phoundation\ErrorHandling\WhoopsRunner;
use Whoops\Run as Whoops;
use Phoundation\Tests\TestAsset\Service\InMemoryLoggerFactory;

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
        $bootstrap = new Bootstrap([
            'config' => [
                'foo' => 'bar',
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
        ]);

        $diContainer = $bootstrap();

        $this->assertInstanceOf(ContainerInterface::class, $diContainer);
        $this->assertInstanceOf(ServiceManager::class, $diContainer);
    }

    /**
     * @test
     */
    public function it_raises_exception_if_di_factory_not_supplied_through_options()
    {
        try {
            new Bootstrap([
                'config' => [
                    'foo' => 'bar',
                ],
            ]);

            $this->fail('Exception should have been raised');
        } catch (\Throwable $ex) {
            $this->assertInstanceOf(InvalidBootstrapOptionsException::class, $ex);
        }
    }

    /**
     * @test
     */
    public function it_puts_config_into_di_container()
    {
        $bootstrap = new Bootstrap([
            'config' => [
                'foo' => 'bar',
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
        ]);

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $config = $diContainer->get(FactoryInterface::DEFAULT_CONFIG_SERVICE_NAME);
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_accepts_config_through_different_ways()
    {
        $bootstrap = new Bootstrap([
            'config' => [
                'foo' => 'bar',
            ],
            'config_paths' => [
                __DIR__ . '/../TestAsset/Config/Glob/global.php'
            ],
            'config_glob_paths' => [
                __DIR__ . '/../TestAsset/Config/Glob/{{,*.}local}.php',
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
        ]);

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $config = $diContainer->get(FactoryInterface::DEFAULT_CONFIG_SERVICE_NAME);
        $this->assertArrayHasKey('foo', $config);
        $this->assertArrayHasKey('db', $config);
    }

    /**
     * @test
     */
    public function it_sets_php_settings_if_provided_through_config()
    {
        $currentTz = ini_get('date.timezone');

        $bootstrap = new Bootstrap([
            'config' => [
                'php_settings' => [
                    'date.timezone' => 'Europe/Belgrade',
                ],
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
        ]);

        $bootstrap();

        $this->assertEquals('Europe/Belgrade', ini_get('date.timezone'));

        ini_set('date.timezone', $currentTz);
    }

    /**
     * @test
     */
    public function it_registers_error_handler_if_provided_through_di_config()
    {
        $bootstrap = new Bootstrap([
            'config' => [
                'di' => [
                    'factories' => [
                        RunnerInterface::class => function () {
                            return new WhoopsRunner(new Whoops());
                        },
                    ]
                ],
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
        ]);

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $this->assertTrue($diContainer->has(RunnerInterface::class));

        $diContainer->get(RunnerInterface::class)->unregister();
    }

    /**
     * @test
     */
    public function it_enables_for_putting_config_under_custom_key_in_di_container()
    {
        $bootstrap = new Bootstrap([
            'config' => [
                'foo' => 'bar',
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
            'config_service_name' => 'AppConfig',
        ]);

        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $config = $diContainer->get('AppConfig');
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_enables_for_having_di_configuration_under_custom_config_key()
    {
        $bootstrap = new Bootstrap([
            'config' => [
                'dependencies' => [
                    'factories' => [
                        'Logger' => InMemoryLoggerFactory::class,
                    ],
                ],
            ],
            'di_container_factory' => ZendServiceManagerFactory::class,
            'di_config_key' => 'dependencies',
        ]);
        
        /* @var $diContainer ContainerInterface */
        $diContainer = $bootstrap();

        $this->assertTrue($diContainer->has('Logger'));
    }
}
