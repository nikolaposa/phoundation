<?php

declare(strict_types=1);

namespace Phoundation\Tests\TestAsset\Service;

use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(): LoggerInterface
    {
        return new InMemoryLogger();
    }
}
