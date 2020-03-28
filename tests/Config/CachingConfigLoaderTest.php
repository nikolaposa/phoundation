<?php

declare(strict_types=1);

namespace Phoundation\Tests\Config;

use Phoundation\Tests\TestAsset\BaseFilesystemTest;
use Phoundation\Config\CachingConfigLoader;
use Phoundation\Config\StaticConfigLoader;

class CachingConfigLoaderTest extends BaseFilesystemTest
{
    /** @var CachingConfigLoader */
    protected $configLoader;

    /** @var string */
    protected $cachedConfigFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cachedConfigFile = $this->directory . DIRECTORY_SEPARATOR . 'config.php';
    }

    /**
     * @test
     */
    public function it_caches_loaded_config_into_file(): void
    {
        $configLoader = new CachingConfigLoader(
            new StaticConfigLoader(['foo' => 'bar']),
            $this->cachedConfigFile
        );

        $config = $configLoader->load();

        $this->assertSame('bar', $config['foo']);
        $this->assertFileExists($this->cachedConfigFile);
        $this->assertFileIsReadable($this->cachedConfigFile);
        $cache = file_get_contents($this->cachedConfigFile);
        $this->assertIsString($cache);
        $this->assertStringContainsString('foo', $cache);
    }
}
