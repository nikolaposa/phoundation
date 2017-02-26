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

use PHPUnit\Framework\TestCase;
use Phoundation\ErrorHandling\Handler\LogHandler;
use Phoundation\Tests\TestAsset\Service\InMemoryLogger;
use Psr\Log\LogLevel;
use RuntimeException;
use ErrorException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class LogHandlerTest extends TestCase
{
    /**
     * @var InMemoryLogger
     */
    protected $logger;

    protected function setUp()
    {
        $this->logger = new InMemoryLogger();
    }

    /**
     * @test
     */
    public function it_logs_exception_message()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new RuntimeException('test'));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals('test', $logs[0]['message']);
    }

    /**
     * @test
     */
    public function it_puts_exception_in_log_context()
    {
        $logHandler = new LogHandler($this->logger);
        $e = new RuntimeException('test');

        $logHandler($e);

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertSame($e, $logs[0]['context']['exception']);
    }

    /**
     * @test
     */
    public function it_logs_exception_with_error_level()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new RuntimeException('test'));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::ERROR, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_core_error_severity_with_error_level()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new ErrorException('test', 0, E_CORE_ERROR));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::ERROR, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_user_warning_severity_with_warning_level()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new ErrorException('test', 0, E_USER_WARNING));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::WARNING, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_notice_severity_with_notice_level()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new ErrorException('test', 0, E_NOTICE));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::NOTICE, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_logs_error_exception_of_deprecated_severity_with_notice_level()
    {
        $logHandler = new LogHandler($this->logger);

        $logHandler(new ErrorException('test', 0, E_DEPRECATED));

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals(LogLevel::NOTICE, $logs[0]['level']);
    }

    /**
     * @test
     */
    public function it_doesnt_log_if_exception_is_in_dont_log_list()
    {
        $logHandler = new LogHandler($this->logger, [
            RuntimeException::class,
        ]);

        $logHandler(new RuntimeException());

        $logs = $this->logger->getLogs();
        $this->assertCount(0, $logs);
    }
}
