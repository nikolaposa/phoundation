<?php

declare(strict_types=1);

namespace Phoundation\Config;

use function Phoundation\mergeConfig;

final class ConfigLoaderAggregate implements ConfigLoader
{
    /** @var ConfigLoader[] */
    private $configLoaders;
    
    public function __construct(ConfigLoader ...$configLoaders)
    {
        $this->configLoaders = $configLoaders;
    }

    public function load(): array
    {
        $config = [];

        foreach ($this->configLoaders as $configLoader) {
            $config = mergeConfig($config, $configLoader->load());
        }

        return $config;
    }
}
