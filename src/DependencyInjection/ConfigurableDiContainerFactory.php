<?php

declare(strict_types=1);

namespace Phoundation\DependencyInjection;

use Psr\Container\ContainerInterface;

abstract class ConfigurableDiContainerFactory implements DiContainerFactory
{
    public function create(array $config): ContainerInterface
    {
        $container = $this->newInstance();

        $this->configure($container, $config);

        return $container;
    }

    abstract protected function newInstance(): ContainerInterface;

    abstract protected function configure(ContainerInterface $container, array $config): void;
}
