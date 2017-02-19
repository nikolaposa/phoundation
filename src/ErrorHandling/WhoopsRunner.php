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

namespace Phoundation\ErrorHandling;

use Whoops\Run;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class WhoopsRunner implements RunnerInterface
{
    /**
     * @var Run
     */
    private $whoops;

    public function __construct(Run $whoops)
    {
        $this->whoops = $whoops;
    }

    public function pushHandler(callable $handler)
    {
        $this->whoops->pushHandler($handler);
    }

    public function register()
    {
        $this->whoops->register();
    }

    public function unregister()
    {
        $this->whoops->unregister();
    }
}
