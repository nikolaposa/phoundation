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

namespace Phoundation\ErrorHandling\Handler;

use Psr\Log\LoggerInterface;
use Throwable;
use Phoundation\Exception\DontLogInterface;
use Psr\Log\LogLevel;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class LogHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $dontLog;

    public function __construct(LoggerInterface $logger, array $dontLog = [])
    {
        $this->logger = $logger;
        $this->dontLog = array_merge($dontLog, [DontLogInterface::class]);
    }

    public function __invoke(Throwable $e)
    {
        if (!$this->shouldLog($e)) {
            return;
        }

        $logLevel = $this->resolveLogLevel($e);
        $message = $e->getMessage();
        $context = [
            'exception' => $e,
        ];

        $this->logger->log($logLevel, $message, $context);
    }

    private function shouldLog(Throwable $e) : bool
    {
        foreach ($this->dontLog as $type) {
            if ($e instanceof $type) {
                return false;
            }
        }

        return true;
    }

    private function resolveLogLevel(Throwable $e)
    {
        if ($e instanceof \ErrorException) {
            switch ($e->getSeverity()) {
                case E_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_PARSE:
                    return LogLevel::ERROR;
                case E_WARNING:
                case E_USER_WARNING:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                    return LogLevel::WARNING;
                case E_NOTICE:
                case E_USER_NOTICE:
                case E_STRICT:
                case E_DEPRECATED:
                case E_USER_DEPRECATED:
                    return LogLevel::NOTICE;
                default:
                    break;
            }
        }

        return LogLevel::ERROR;
    }
}
