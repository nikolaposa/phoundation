<?php

declare(strict_types=1);

namespace Phoundation\Tests\Config;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\ConfigLoaderAggregate;
use Phoundation\Config\StaticConfigLoader;

class ConfigLoaderAggregateTest extends TestCase
{
    /**
     * @test
     */
    public function it_merges_multiple_configurations(): void
    {
        $configLoader = new ConfigLoaderAggregate(
            new StaticConfigLoader([
                'foo' => 'bar',
            ]),
            new StaticConfigLoader([
                'key' => 'test',
            ]),
            new StaticConfigLoader([
                'foo' => 'baz'
            ])
        );

        $config = $configLoader->load();

        $this->assertSame('baz', $config['foo']);
        $this->assertSame('test', $config['key']);
    }
}
