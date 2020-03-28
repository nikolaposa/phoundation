<?php

declare(strict_types=1);

namespace Phoundation\Tests\TestAsset\Service;

use Psr\Log\AbstractLogger;

final class InMemoryLogger extends AbstractLogger
{
    /** @var array */
    private $logs = [];

    public function log($level, $message, array $context = []): void
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}
