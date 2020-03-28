<?php

declare(strict_types=1);

namespace Phoundation\Config;

final class StaticConfigLoader implements ConfigLoader
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function load(): array
    {
        return $this->config;
    }
}
