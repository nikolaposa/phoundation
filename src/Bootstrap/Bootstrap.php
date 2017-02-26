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

use Phoundation\Config\Loader\ConfigLoaderInterface;
use Phoundation\Di\Container\Factory\DiContainerFactoryInterface;
use Phoundation\Config\Config;
use Psr\Container\ContainerInterface;
use Phoundation\ErrorHandling\RunnerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Bootstrap
{
    /**
     * @var ConfigLoaderInterface
     */
    protected $configLoader;

    /**
     * @var DiContainerFactoryInterface
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

    public function __construct(ConfigLoaderInterface $configLoader, DiContainerFactoryInterface $diContainerFactory)
    {
        $this->configLoader = $configLoader;
        $this->diContainerFactory = $diContainerFactory;
    }

    public function __invoke()
    {
        $this->loadConfig();
        $this->buildDiContainer();

        $this->setPhpSettings();
        $this->registerErrorHandler();

        return $this->diContainer;
    }

    protected function loadConfig()
    {
        $configLoader = $this->configLoader;
        $this->config = $configLoader();
    }

    protected function buildDiContainer()
    {
        $diContainerFactory = $this->diContainerFactory;

        $this->diContainer = $diContainerFactory($this->config);
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
