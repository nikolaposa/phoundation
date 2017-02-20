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

namespace Phoundation\Bootstrap;

use Phoundation\Config\Config;
use Psr\Container\ContainerInterface;
use Phoundation\Di\Container\Factory\FactoryInterface;
use Phoundation\Config\Loader\GlobConfigLoader;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;
use Phoundation\Di\Container\Factory\AuraDiFactory;
use Phoundation\Di\Container\Factory\PimpleFactory;
use Phoundation\ErrorHandling\RunnerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Bootstrap
{
    /**
     * @var callable
     */
    protected $configLoader;

    /**
     * @var callable
     */
    protected $diContainerFactory;

    /**
     * @var string
     */
    protected $diConfigKey;

    /**
     * @var string
     */
    protected $configServiceName;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ContainerInterface
     */
    protected $diContainer;

    /**
     * @var array
     */
    protected static $configLoaders = [
        'glob' => GlobConfigLoader::class,
    ];

    /**
     * @var array
     */
    protected static $diContainerFactories = [
        'zend-service-manager' => ZendServiceManagerFactory::class,
        'aura-di' => AuraDiFactory::class,
        'pimple' => PimpleFactory::class,
    ];

    /**
     * @param callable|string $configLoader
     * @param callable|string $diContainerFactory
     * @param string $diConfigKey
     * @param string $configServiceName
     */
    protected function __construct($configLoader, $diContainerFactory, string $diConfigKey, string $configServiceName)
    {
        if (is_string($configLoader)) {
            if (array_key_exists($configLoader, static::$configLoaders)) {
                $configLoader = static::$configLoaders[$configLoader];
            }

            $configLoader = new $configLoader();
        }

        if (!is_callable($configLoader)) {
        }

        if (is_string($diContainerFactory)) {
            if (array_key_exists($diContainerFactory, static::$diContainerFactories)) {
                $diContainerFactory = static::$diContainerFactories[$diContainerFactory];
            }

            $diContainerFactory = new $diContainerFactory();
        }

        if (!is_callable($diContainerFactory)) {
        }

        $this->configLoader = $configLoader;
        $this->diContainerFactory = $diContainerFactory;
        $this->diConfigKey = $diConfigKey;
        $this->configServiceName = $configServiceName;
    }

    public static function init(array $options)
    {
        return new self(
            $options['config_loader'],
            $options['di_container_factory'],
            $options['di_config_key'] ?? FactoryInterface::DEFAULT_DI_CONFIG_KEY,
            $options['config_service_name'] ?? FactoryInterface::DEFAULT_CONFIG_SERVICE_NAME
        );
    }

    public function run(array $configPaths)
    {
        $configLoader = $this->configLoader;
        $diContainerFactory = $this->diContainerFactory;

        $this->config = $configLoader($configPaths);

        $this->diContainer = $diContainerFactory($this->config);

        $this->setPhpSettings();
        $this->registerErrorHandler();

        return $this->diContainer;
    }

    protected function setPhpSettings()
    {
        $phpSettings = (array) ($this->config['php_settings'] ?? []);

        foreach ($phpSettings as $key => $value) {
            ini_set($key, $value);
        }
    }

    protected function registerErrorHandler()
    {
        if (!$this->diContainer->has(RunnerInterface::class)) {
            return;
        }

        $this->diContainer->get(RunnerInterface::class)->register();
    }
}
