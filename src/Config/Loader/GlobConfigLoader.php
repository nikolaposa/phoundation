<?php
/**
 * This file is part of the PHP Application Skeleton package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Foundation\Config\Loader;

use Foundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class GlobConfigLoader extends AbstractLoader
{
    /**
     * @var string
     */
    private $globPath;

    public function __construct(string $globPath)
    {
        $this->globPath = $globPath;
    }

    public function __invoke() : Config
    {
        $config = [];

        foreach (glob($this->globPath, GLOB_BRACE) as $file) {
            $config = self::arrayMerge($config, include $file);
        }

        return Config::fromArray($config);
    }
}
