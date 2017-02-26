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

namespace Phoundation\Config\Loader;

use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class CachingConfigLoader implements ConfigLoaderInterface
{
    /**
     * @var ConfigLoaderInterface
     */
    private $configLoader;

    /**
     * @var string
     */
    private $cachedConfigFile;
    
    public function __construct(ConfigLoaderInterface $configLoader, string $cachedConfigFile)
    {
        $this->configLoader = $configLoader;
        $this->cachedConfigFile = $cachedConfigFile;
    }

    public function __invoke() : Config
    {
        if (is_file($this->cachedConfigFile)) {
            return Config::fromArray(include $this->cachedConfigFile);
        }

        $configLoader = $this->configLoader;
        /* @var $config Config */
        $config = $configLoader();

        file_put_contents($this->cachedConfigFile, '<?php return ' . var_export($config->toArray(), true) . ';');

        return $config;
    }
}
