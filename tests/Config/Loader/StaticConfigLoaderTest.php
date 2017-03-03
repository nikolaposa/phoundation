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
use Phoundation\Config\Loader\StaticConfigLoader;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class StaticConfigLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_provided_config_array()
    {
        $configLoader = new StaticConfigLoader(['foo' => 'bar']);

        $config = $configLoader();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('bar', $config['foo']);
    }
}
