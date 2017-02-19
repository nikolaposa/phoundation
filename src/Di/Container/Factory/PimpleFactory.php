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
            $this->container[$name] = function ($container) use ($factory, $name) {
                /* @var $container Container */

                if ($container->has($factory)) {
                    $factory = $container->get($factory);
                } else {
                    $factory = new $factory();
                    $container[$factory] = $container->protect($factory);
                }

                return $factory($container, $name);
            };
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
