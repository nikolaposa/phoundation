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

namespace Phoundation\Tests\Config;

use PHPUnit\Framework\TestCase;
use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_created_from_array()
    {
        $config = Config::fromArray(['foo' => 'bar']);

        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_can_accessed_using_array_notation()
    {
        $config = Config::fromArray([
            'db' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'secret',
                'dbname' => 'test',
            ],
        ]);

        $this->assertEquals('pdo_mysql', $config['db']['driver']);
    }
}
