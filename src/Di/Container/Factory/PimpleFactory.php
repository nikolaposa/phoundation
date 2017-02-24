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

use Xtreamwayz\Pimple\Container;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class PimpleFactory extends AbstractFactory
{
    /**
     * @var Container
     */
    private $container;

    protected function createContainer()
    {
        return new Container();
    }

    protected function configure($container)
    {
        $this->container = $container;

        $this->setFactories();
        $this->setInvokables();

        $this->container[$this->getConfigServiceName()] = $this->getConfig();
    }

    private function setFactories()
    {
        foreach ($this->getDiConfig('factories') as $name => $factory) {
            if (is_string($factory)) {
                $factoryName = $factory;

                $this->container[$name] = function ($container) use ($factoryName, $name) {
                    /* @var $container Container */

                    if ($container->has($factoryName)) {
                        $factory = $container->get($factoryName);
                    } else {
                        $factory = new $factoryName();
                        $container[$factoryName] = $container->protect($factory);
                    }

                    return $factory($container, $name);
                };

                continue;
            }

            $this->container[$name] = $factory;
        }
    }

    private function setInvokables()
    {
        foreach ($this->getDiConfig('invokables') as $name => $className) {
            $this->container[$name] = function () use ($className) {
                return new $className();
            };
        }
    }
}
