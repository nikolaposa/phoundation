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

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class AuraDiFactory extends AbstractFactory
{
    /**
     * @var Container
     */
    protected $container;

    protected function createContainer()
    {
        return (new ContainerBuilder())->newInstance();
    }

    protected function configure($container)
    {
        $this->container = $container;
        
        $this->setFactories();
        $this->setInvokables();

        $this->container->set(self::CONFIG_SERVICE_NAME, $this->getConfig());
    }

    private function setFactories()
    {
        foreach ($this->getDiConfig('factories') as $name => $factory) {
            if (is_string($factory)) {
                $this->container->set($name, $this->container->lazyNew($factory));
                $this->container->set($name, $this->container->lazyGetCall($factory, '__invoke', $this->container));
                continue;
            }

            $this->container->set($name, $factory);
        }
    }

    private function setInvokables()
    {
        foreach ($this->getDiConfig('invokables') as $name => $className) {
            $this->container->set($name, $this->container->lazyNew($className));
        }
    }
}
