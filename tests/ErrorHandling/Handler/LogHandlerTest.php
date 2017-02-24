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

namespace Phoundation\Tests\Config\Loader;

use Phoundation\Exception\DontLogInterface;
use PHPUnit\Framework\TestCase;
use Phoundation\ErrorHandling\Handler\LogHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use RuntimeException;
use ErrorException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class LogHandlerTest extends TestCase
{
    /**
     * @var LogHandler
     */
    protected $logHandler;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected function setUp()
    {
        $this->logger = new class extends AbstractLogger {
            protected $logs = [];

            public function log($level, $message, array $context = array())
            {
                $this->logs[] = [
                    'level' => $level,
                    'message' => $message,
                    'context' => $context,
                ];
            }

            public function getLogs()
            {
                return $this->logs;
            }
        };

        $this->logHandler = new LogHandler($this->logger);
    }

    /**
     * @test
     */
    public function it_logs_exception_message()
    {
        $this->logHandler->__invoke(new RuntimeException('test'));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals('test', $logs[0]['message']);
    }

    /**
     * @test
     */
    public function it_puts_exception_in_log_context()
    {
        $e = new RuntimeException('test');
        $this->logHandler->__invoke($e);

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertSame($e, $logs[0]['context']['exception']);
    }

    /**
     * @test
     */
    public function it_logs_exception_with_error_level()
    {
        $this->logHandler->__invoke(new RuntimeException('test'));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::ERROR, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_core_error_severity_with_error_level()
    {
        $this->logHandler->__invoke(new ErrorException('test', 0, E_CORE_ERROR));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::ERROR, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_user_warning_severity_with_warning_level()
    {
        $this->logHandler->__invoke(new ErrorException('test', 0, E_USER_WARNING));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::WARNING, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_notice_severity_with_notice_level()
    {
        $this->logHandler->__invoke(new ErrorException('test', 0, E_NOTICE));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::NOTICE, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_deprecated_severity_with_notice_level()
    {
        $this->logHandler->__invoke(new ErrorException('test', 0, E_DEPRECATED));

        $logs = $this->logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::NOTICE, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_doesnt_log_if_exception_is_in_dont_log_list()
    {
        $ex = new class extends RuntimeException implements DontLogInterface {
        };

        $this->logHandler->__invoke($ex);

        $logs = $this->logger->getLogs();

        $this->assertCount(0, $logs);
    }
}
