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

namespace Foundation\Bootstrap;

use Foundation\Config\Config;
use Psr\Container\ContainerInterface;
use Foundation\Di\Container\Factory\ZendServiceManagerFactory;
use Foundation\Di\Container\Factory\AuraDiFactory;
use Foundation\Di\Container\Factory\PimpleFactory;
use Foundation\ErrorHandling\RunnerInterface;

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
    protected static $diContainerFactories = [
        'zend-service-manager' => ZendServiceManagerFactory::class,
        'aura-di' => AuraDiFactory::class,
        'pimple' => PimpleFactory::class,
    ];

    public function __construct(callable $configLoader, callable $diContainerFactory)
    {
        $this->configLoader = $configLoader;
        $this->diContainerFactory = $diContainerFactory;
    }

    public function __invoke()
    {
        $configLoader = $this->configLoader;
        $diContainerFactory = $this->diContainerFactory;

        $this->config = $configLoader();

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
