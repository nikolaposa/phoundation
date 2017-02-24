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

use Phoundation\Config\Loader\LoaderInterface;
use Phoundation\Config\Loader\GlobConfigLoader;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class GlobConfigLoaderTest extends ConfigLoaderTest
{
    protected function createConfigLoader() : LoaderInterface
    {
        return new GlobConfigLoader();
    }

    /**
     * @test
     */
    public function it_merges_configurations_in_correct_order()
    {
        $config = $this->configLoader->__invoke([
            __DIR__ . '/../../TestAsset/Config/Glob/{{,*.}global,{,*.}local}.php',
        ]);

        $this->assertArrayHasKey('db', $config);
        $this->assertEquals('test', $config['db']['user']);
    }
}
