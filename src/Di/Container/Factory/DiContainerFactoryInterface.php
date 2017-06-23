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

namespace Phoundation\Di\Container\Factory;

use Phoundation\Config\Config;
use Psr\Container\ContainerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface DiContainerFactoryInterface
{
    const DEFAULT_DI_CONFIG_KEY = 'di';
    const DEFAULT_CONFIG_SERVICE_NAME = 'config';

    public function __invoke(Config $config) : ContainerInterface;
}
