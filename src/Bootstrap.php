<?php

declare(strict_types=1);

namespace Phoundation;

use Phoundation\Config\ConfigLoader;
use Phoundation\DependencyInjection\DiContainerFactory;
use Psr\Container\ContainerInterface;

class Bootstrap
{
    /** @var ConfigLoader */
    protected $configLoader;

    /** @var DiContainerFactory */
    protected $diContainerFactory;

    public function __construct(ConfigLoader $configLoader, DiContainerFactory $diContainerFactory)
    {
        $this->configLoader = $configLoader;
        $this->diContainerFactory = $diContainerFactory;
    }

    public function __invoke(): ContainerInterface
    {
        $config = $this->configLoader->load();

        return $this->diContainerFactory->create($config);
    }
}
