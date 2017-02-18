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

namespace Foundation\Tests\Config\Loader;

use PHPUnit\Framework\TestCase;
use Foundation\ErrorHandling\RunnerInterface;
use Foundation\ErrorHandling\WhoopsRunner;
use Whoops\Run;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class WhoopsRunnerTest extends TestCase
{
    /**
     * @var RunnerInterface
     */
    protected $runner;

    /**
     * @var Run
     */
    protected $whoops;

    protected function setUp()
    {
        $this->whoops = new Run();
        $this->runner = new WhoopsRunner($this->whoops);
    }

    /**
     * @test
     */
    public function it_pushes_handler()
    {
        $this->runner->pushHandler(function ($ex) {
        });

        $this->assertCount(1, $this->whoops->getHandlers());
    }
}
