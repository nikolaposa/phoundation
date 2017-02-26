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
use Phoundation\Config\ConfigFactory;
use Phoundation\ErrorHandling\RunnerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Bootstrap
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ContainerInterface
     */
    protected $diContainer;

    public function __construct(array $options)
    {
        $this->options = Options::fromArray($options);
    }

    public function __invoke()
    {
        $this->buildConfig();
        $this->buildDiContainer();

        $this->setPhpSettings();
        $this->registerErrorHandler();

        return $this->diContainer;
    }

    protected function buildConfig()
    {
        $config = Config::fromArray($this->options->getConfig());

        foreach ($this->options->getConfigPaths() as $path) {
            $config->merge(ConfigFactory::createFromPath($path));
        }

        foreach ($this->options->getConfigGlobPaths() as $globPath) {
            $config->merge(ConfigFactory::createFromGlobPath($globPath));
        }

        $this->config = $config;
    }

    protected function buildDiContainer()
    {
        $diContainerFactory = $this->options->getDiContainerFactory();

        $this->diContainer = $diContainerFactory($this->config, $this->options->getDiConfigKey(), $this->options->getConfigServiceName());
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
