<?php

declare(strict_types=1);

namespace Phoundation\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Phoundation\DependencyInjection\DiContainerFactory;

abstract class DiContainerFactoryTest extends TestCase
{
    /** @var DiContainerFactory */
    protected $factory;

    protected function setUp(): void
    {
        $this->factory = $this->createFactory();
    }

    abstract protected function createFactory(): DiContainerFactory;
}
