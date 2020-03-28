<?php

declare(strict_types=1);

namespace Phoundation\Tests\Config;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\FileConfigLoader;

class FileConfigLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_config_from_path(): void
    {
        $configLoader = new FileConfigLoader([
            __DIR__ . '/../TestAsset/Config/global.php',
        ]);

        $config = $configLoader->load();

        $this->assertSame('localhost', $config['db']['host']);
    }

    /**
     * @test
     */
    public function it_loads_config_from_glob_path(): void
    {
        $configLoader = new FileConfigLoader(glob(__DIR__ . '/../TestAsset/Config/{{,*.}global,{,*.}local}.php', GLOB_BRACE));

        $config = $configLoader->load();

        $this->assertSame('test', $config['db']['user']);
    }

    /**
     * @test
     */
    public function it_merges_multiple_configurations(): void
    {
        $configLoader = new FileConfigLoader(glob(__DIR__ . '/../TestAsset/Config/{{,*.}global,{,*.}local}.php', GLOB_BRACE));

        $config = $configLoader->load();

        $this->assertSame(['bar', 'baz'], $config['foo']);
    }
}
