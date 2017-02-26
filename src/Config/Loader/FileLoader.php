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
final class FileLoader extends AbstractLoader
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var array
     */
    private $config = [];

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function __invoke() : Config
    {
        foreach ($this->paths as $path) {
            $this->loadFromPath($path);
        }

        return Config::fromArray($this->config);
    }

    private function loadFromPath(string $path)
    {
        $this->config = self::arrayMerge($this->config, include $path);
    }
}
