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

namespace Phoundation\Tests\Config\Loader;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\Loader\LoaderInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class ConfigLoaderTest extends TestCase
{
    /**
     * @var LoaderInterface
     */
    protected $configLoader;

    protected function setUp()
    {
        $this->configLoader = $this->createConfigLoader();
    }

    abstract protected function createConfigLoader() : LoaderInterface;

    /**
     * @test
     */
    public function it_merges_config_elements_with_numeric_keys()
    {
        $config = $this->configLoader->__invoke([
            __DIR__ . '/../../TestAsset/Config/Glob/global.php',
            __DIR__ . '/../../TestAsset/Config/Glob/local.php',
        ]);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals(['bar', 'baz'], $config['foo']);
    }
}
