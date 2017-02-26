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
use Phoundation\Config\Loader\FileLoader;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class FileLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_config_from_path()
    {
        $loader = new FileLoader([
            __DIR__ . '/../../TestAsset/Config/global.php',
        ]);

        $config = $loader();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('localhost', $config['db']['host']);
    }

    /**
     * @test
     */
    public function it_loads_config_from_glob_path()
    {
        $loader = new FileLoader(glob(__DIR__ . '/../../TestAsset/Config/{{,*.}global,{,*.}local}.php', GLOB_BRACE));

        $config = $loader();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('test', $config['db']['user']);
    }
}
