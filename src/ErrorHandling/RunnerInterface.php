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

namespace Foundation\ErrorHandling;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface RunnerInterface
{
    public function pushHandler(callable $handler);
    
    public function register();

    public function unregister();
}
