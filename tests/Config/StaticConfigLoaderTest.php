<?php

declare(strict_types=1);

namespace Phoundation\Tests\Config;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\StaticConfigLoader;

class StaticConfigLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_provided_config_array(): void
    {
        $configLoader = new StaticConfigLoader(['foo' => 'bar']);

        $config = $configLoader->load();

        $this->assertSame('bar', $config['foo']);
    }
}
