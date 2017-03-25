<?php
/**
 * This file is part of the Phoundation package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Phoundation\Tests\Config;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\Loader\ConfigLoaderAggregate;
use Phoundation\Config\Loader\StaticConfigLoader;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ConfigLoaderAggregateTest extends TestCase
{
    /**
     * @test
     */
    public function it_merges_multiple_configurations()
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

        $config = $configLoader();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('baz', $config['foo']);
        $this->assertEquals('test', $config['key']);
    }
}
