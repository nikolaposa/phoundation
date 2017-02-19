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
use Phoundation\ErrorHandling\RunnerInterface;
use Phoundation\ErrorHandling\WhoopsRunner;
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
