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

namespace Phoundation\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ConfigFactory
{
    public static function createFromPath(string $path) : Config
    {
        return Config::fromArray(include $path);
    }

    public static function createFromGlobPath(string $globPath) : Config
    {
        $config = Config::fromArray([]);

        foreach (glob($globPath, GLOB_BRACE) as $path) {
            $config->merge(self::createFromPath($path));
        }

        return $config;
    }
}
