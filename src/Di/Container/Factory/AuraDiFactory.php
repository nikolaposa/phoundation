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

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class AuraDiFactory extends AbstractFactory
{
    /**
     * @var Container
     */
    protected $container;

    protected function createContainer() : ContainerInterface
    {
        return (new ContainerBuilder())->newInstance();
    }

    protected function configure(ContainerInterface $container)
    {
        $this->container = $container;
        
        $this->setFactories();
        $this->setInvokables();

        $this->container->set($this->getConfigServiceName(), $this->getConfig());
    }

    private function setFactories()
    {
        foreach ($this->getDiConfigGroup('factories') as $name => $factory) {
            if (is_string($factory)) {
                $this->container->set($factory, $this->container->lazyNew($factory));
                $this->container->set($name, $this->container->lazyGetCall($factory, '__invoke', $this->container));
                continue;
            }

            $this->container->set($name, $factory);
        }
    }

    private function setInvokables()
    {
        foreach ($this->getDiConfigGroup('invokables') as $name => $className) {
            $this->container->set($name, $this->container->lazyNew($className));
        }
    }
}
