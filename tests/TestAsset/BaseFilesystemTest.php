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

namespace Phoundation\Tests\TestAsset;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class BaseFilesystemTest extends TestCase
{
    /**
     * @var string
     */
    protected $directory;

    protected function setUp()
    {
        $this->createTestDirectory();
    }

    protected function tearDown()
    {
        $this->removeTestDirectory();
    }

    final protected function createTestDirectory()
    {
        $this->directory = sys_get_temp_dir() . '/phoundation_' . uniqid();

        if (!is_dir($this->directory)) {
            if (false === @mkdir($this->directory, 0777, true) && !is_dir($this->directory)) {
                $this->fail('Test directory cannot be created');
            }
        }
    }

    final protected function removeTestDirectory()
    {
        if (!is_dir($this->directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->directory),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                @unlink($fileInfo->getRealPath());
            } elseif ($fileInfo->isDir()) {
                @rmdir($fileInfo->getRealPath());
            }
        }

        @rmdir($this->directory);
    }
}
