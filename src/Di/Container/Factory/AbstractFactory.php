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

namespace Phoundation\Di\Container\Factory;

use Interop\Container\ContainerInterface;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractFactory implements DiContainerFactoryInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var Config
     */
    private $config;

    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'di_config_key' => self::DEFAULT_DI_CONFIG_KEY,
            'config_service_name' => self::DEFAULT_CONFIG_SERVICE_NAME,
        ], $options);
    }

    public function __invoke(Config $config) : ContainerInterface
    {
        $this->config = $config;

        $container = $this->createContainer();

        $this->configure($container);

        return $container;
    }

    abstract protected function createContainer();

    abstract protected function configure($container);

    final protected function getConfig()
    {
        return $this->config;
    }

    final protected function getConfigServiceName()
    {
        return $this->options['config_service_name'];
    }

    final protected function getDiConfig() : array
    {
        $diConfigKey = $this->options['di_config_key'];
        
        if (empty($this->config[$diConfigKey]) || !is_array($this->config[$diConfigKey])) {
            return [];
        }

        return $this->config[$diConfigKey];
    }

    final protected function getDiConfigGroup(string $group) : array
    {
        $diConfig = $this->getDiConfig();

        if (empty($diConfig[$group]) || !is_array($diConfig[$group])) {
            return [];
        }

        return $diConfig[$group];
    }
}
