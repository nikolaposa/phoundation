<?php

declare(strict_types=1);

namespace Phoundation\DependencyInjection;

use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;

final class LaminasServiceManagerFactory extends ConfigurableDiContainerFactory
{
    protected function newInstance(): ContainerInterface
    {
        return new ServiceManager();
    }

    protected function configure(ContainerInterface $container, array $config): void
    {
        if (!$container instanceof ServiceManager) {
            return;
        }

        if (isset($config['dependencies']) && is_array($config['dependencies'])) {
            $container->configure($config['dependencies']);
        }

        $container->setService('config', $config);
    }
}
