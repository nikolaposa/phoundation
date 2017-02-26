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

use ArrayObject;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Config extends ArrayObject
{
    public static function fromArray(array $config)
    {
        return new self($config);
    }

    public function merge(Config $config)
    {
        $mergedConfig = self::arrayMerge($this->getArrayCopy(), $config->getArrayCopy());

        $this->exchangeArray($mergedConfig);
    }

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
            } else {
                $a[$key] = $value;
            }
        }

        return $a;
    }
}
