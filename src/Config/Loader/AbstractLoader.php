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

namespace Phoundation\Config\Loader;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractLoader implements LoaderInterface
{
    final protected static function arrayMerge(array $a, array $b) : array
    {
        foreach ($b as $key => $value) {
            if (array_key_exists($key, $a)) {
                if (is_int($key)) {
                    $a[] = $value;
                } elseif (is_array($value) && is_array($a[$key])) {
                    $a[$key] = self::arrayMerge($a[$key], $value);
                } else {
                    $a[$key] = $value;
                }
            }
        }

        return $a;
    }
}
