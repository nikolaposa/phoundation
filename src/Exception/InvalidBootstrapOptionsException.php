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

namespace Phoundation\Exception;

use InvalidArgumentException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class InvalidBootstrapOptionsException extends InvalidArgumentException implements ExceptionInterface
{
    public static function forMissingDiContainerFactory()
    {
        return new self("'di_container_factory must be supplied and should be either callable or string (name of the factory class)'");
    }
}
