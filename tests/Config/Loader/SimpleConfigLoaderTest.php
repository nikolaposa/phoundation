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
use Phoundation\Config\Loader\SimpleConfigLoader;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class SimpleConfigLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_merges_multiple_configuration_files()
    {
        $configLoader = new SimpleConfigLoader();

        $config = $configLoader([
            __DIR__ . '/../../TestAsset/Config/Glob/global.php',
            __DIR__ . '/../../TestAsset/Config/Glob/local.php',
        ]);

        $this->assertArrayHasKey('db', $config);
        $this->assertEquals('test', $config['db']['user']);
    }
}
