<?php

declare(strict_types=1);

namespace Phoundation\Config;

final class CachingConfigLoader implements ConfigLoader
{
    /** @var ConfigLoader */
    private $configLoader;

    /** @var string */
    private $cachedConfigFile;
    
    public function __construct(ConfigLoader $configLoader, string $cachedConfigFile)
    {
        $this->configLoader = $configLoader;
        $this->cachedConfigFile = $cachedConfigFile;
    }

    public function load(): array
    {
        if (is_file($this->cachedConfigFile)) {
            return require $this->cachedConfigFile;
        }

        $config = $this->configLoader->load();

        file_put_contents($this->cachedConfigFile, '<?php return ' . var_export($config, true) . ';');

        return $config;
    }
}
