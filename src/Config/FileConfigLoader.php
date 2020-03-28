<?php

declare(strict_types=1);

namespace Phoundation\Config;

use function Phoundation\mergeConfig;

final class FileConfigLoader implements ConfigLoader
{
    /** @var array */
    private $paths;

    /** @var array */
    private $config = [];

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function load(): array
    {
        foreach ($this->paths as $path) {
            $this->loadFromPath($path);
        }

        return $this->config;
    }

    private function loadFromPath(string $path): void
    {
        $config = require $path;
        $this->config = mergeConfig($this->config, $config);
    }
}
