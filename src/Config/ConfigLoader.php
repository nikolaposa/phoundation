<?php

declare(strict_types=1);

namespace Phoundation\Config;

interface ConfigLoader
{
    public function load(): array;
}
