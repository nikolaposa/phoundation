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
final class ConfigLoaderAggregate extends AbstractLoader
{
    /**
     * @var ConfigLoaderInterface[]
     */
    private $configLoaders;
    
    public function __construct(ConfigLoaderInterface ...$configLoaders)
    {
        $this->configLoaders = $configLoaders;
    }

    public function __invoke() : Config
    {
        $config = [];

        foreach ($this->configLoaders as $configLoader) {
            /* @var $subConfig Config */
            $subConfig = $configLoader();

            $config = self::arrayMerge($config, $subConfig->toArray());
        }

        return Config::fromArray($config);
    }
}
