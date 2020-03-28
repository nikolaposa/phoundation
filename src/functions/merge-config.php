<?php

declare(strict_types=1);

namespace Phoundation;

function mergeConfig(array $config1, array $config2): array
{
    foreach ($config2 as $key => $value) {
        if (array_key_exists($key, $config1)) {
            if (is_int($key)) {
                $config1[] = $value;
            } elseif (is_array($value) && is_array($config1[$key])) {
                $config1[$key] = mergeConfig($config1[$key], $value);
            } else {
                $config1[$key] = $value;
            }
        } else {
            $config1[$key] = $value;
        }
    }

    return $config1;
}
