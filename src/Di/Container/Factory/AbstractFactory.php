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

namespace Foundation\Di\Container\Factory;

use Foundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __invoke(Config $config)
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

    final protected function getDiConfig(string $type = null) : array
    {
        if (null === $type) {
            return $this->config[self::CONFIG_KEY] ?? [];
        }

        if (empty($this->config[self::CONFIG_KEY][$type]) || !is_array($this->config[self::CONFIG_KEY][$type])) {
            return [];
        }

        return $this->config[self::CONFIG_KEY][$type];
    }
}
