<?php

declare(strict_types=1);

namespace Phoundation\DependencyInjection;

use Psr\Container\ContainerInterface;

interface DiContainerFactory
{
    public function create(array $config): ContainerInterface;
}
