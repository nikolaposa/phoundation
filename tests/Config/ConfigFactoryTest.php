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
use Phoundation\Config\ConfigFactory;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ConfigFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_config_from_path()
    {
        $config = ConfigFactory::createFromPath(__DIR__ . '/../TestAsset/Config/Glob/global.php');

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('localhost', $config['db']['host']);
    }

    /**
     * @test
     */
    public function it_creates_config_from_glob_path()
    {
        $config = ConfigFactory::createFromGlobPath(__DIR__ . '/../TestAsset/Config/Glob/{{,*.}global,{,*.}local}.php');

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('test', $config['db']['user']);
    }
}
