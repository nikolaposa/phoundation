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

use Phoundation\Tests\TestAsset\BaseFilesystemTest;
use Phoundation\Config\Loader\CachingConfigLoader;
use Phoundation\Config\Loader\StaticConfigLoader;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class CachingConfigLoaderTest extends BaseFilesystemTest
{
    /**
     * @var CachingConfigLoader
     */
    protected $configLoader;

    /**
     * @var string
     */
    protected $cachedConfigFile;

    protected function setUp()
    {
        parent::setUp();

        $this->cachedConfigFile = $this->directory . DIRECTORY_SEPARATOR . 'config.php';
    }

    /**
     * @test
     */
    public function it_caches_loaded_config_into_file()
    {
        $configLoader = new CachingConfigLoader(
            new StaticConfigLoader(['foo' => 'bar']),
            $this->cachedConfigFile
        );

        $config = $configLoader();

        $this->assertInstanceOf(Config::class, $config);
        $this->assertFileExists($this->cachedConfigFile);
        $this->assertFileIsReadable($this->cachedConfigFile);
        $this->assertContains('foo', file_get_contents($this->cachedConfigFile));
    }

    /**
     * @test
     */
    public function it_loads_config_from_cache_on_subsequent_request()
    {
        $configLoader = new CachingConfigLoader(
            new StaticConfigLoader(['foo' => 'bar']),
            $this->cachedConfigFile
        );

        $configLoader();
        $config = $configLoader();

        $this->assertInstanceOf(Config::class, $config);
    }
}
