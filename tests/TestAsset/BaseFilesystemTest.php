<?php

declare(strict_types=1);

namespace Phoundation\Tests\TestAsset;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class BaseFilesystemTest extends TestCase
{
    /** @var string */
    protected $directory;

    protected function setUp(): void
    {
        $this->createTestDirectory();
    }

    protected function tearDown(): void
    {
        $this->removeTestDirectory();
    }

    final protected function createTestDirectory(): void
    {
        $this->directory = sys_get_temp_dir() . '/' . uniqid('phoundation', true);

        if (!is_dir($this->directory)) {
            if (false === @mkdir($this->directory, 0777, true) && !is_dir($this->directory)) {
                $this->fail('Test directory cannot be created');
            }
        }
    }

    final protected function removeTestDirectory(): void
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
