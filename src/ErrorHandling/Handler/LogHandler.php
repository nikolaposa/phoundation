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

    /**
     * @var array
     */
    private static $errorSeverityLogLevelMap = [
        E_ERROR => LogLevel::ERROR,
        E_RECOVERABLE_ERROR => LogLevel::ERROR,
        E_CORE_ERROR => LogLevel::ERROR,
        E_COMPILE_ERROR => LogLevel::ERROR,
        E_USER_ERROR => LogLevel::ERROR,
        E_PARSE => LogLevel::ERROR,
        E_WARNING => LogLevel::WARNING,
        E_USER_WARNING => LogLevel::WARNING,
        E_CORE_WARNING => LogLevel::WARNING,
        E_COMPILE_WARNING => LogLevel::WARNING,
        E_NOTICE => LogLevel::NOTICE,
        E_USER_NOTICE => LogLevel::NOTICE,
        E_STRICT => LogLevel::NOTICE,
        E_DEPRECATED => LogLevel::NOTICE,
        E_USER_DEPRECATED => LogLevel::NOTICE,
    ];

    public function __construct(LoggerInterface $logger, array $dontLog = [])
    {
        $this->logger = $logger;
        $this->dontLog = $dontLog;
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
            return self::$errorSeverityLogLevelMap[$e->getSeverity()] ?? LogLevel::ERROR;
        }

        return LogLevel::ERROR;
    }
}
