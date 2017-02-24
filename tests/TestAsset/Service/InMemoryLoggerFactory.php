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

namespace Phoundation\Tests\TestAsset\Service;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class InMemoryLoggerFactory
{
    public function __invoke()
    {
        return new InMemoryLogger();
    }
}
