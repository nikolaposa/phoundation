<?php
/**
 * This file is part of the PHP App Foundation package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Foundation\ErrorHandling\Handler;

use Psr\Log\LoggerInterface;
use Throwable;
use ErrorException;
use Foundation\Exception\DontLogInterface;
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

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Throwable $e)
    {
        if ($e instanceof DontLogInterface) {
            return;
        }

        $logLevel = $this->resolveLogLevel($e);
        $message = $e->getMessage();
        $context = [
            'exception' => $e,
        ];

        $this->logger->log($logLevel, $message, $context);
    }

    private function resolveLogLevel(Throwable $e)
    {
        if ($e instanceof ErrorException) {
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
