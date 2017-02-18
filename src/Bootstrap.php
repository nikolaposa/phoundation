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

namespace Foundation;

use Psr\Container\ContainerInterface;
use Foundation\Di\Container\Factory\ZendServiceManagerFactory;
use Foundation\Di\Container\Factory\AuraDiFactory;
use Foundation\Di\Container\Factory\PimpleFactory;
use Foundation\ErrorHandling\RunnerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Bootstrap
{
    /**
     * @var callable
     */
    protected $configLoader;

    /**
     * @var callable
     */
    protected $diContainerFactory;

    /**
     * @var array
     */
    protected static $diContainerFactories = [
        'zend-service-manager' => ZendServiceManagerFactory::class,
        'aura-di' => AuraDiFactory::class,
        'pimple' => PimpleFactory::class,
    ];

    public function __construct(callable $configLoader, callable $diContainerFactory)
    {
        $this->configLoader = $configLoader;
        $this->diContainerFactory = $diContainerFactory;
    }

    public function __invoke()
    {
        $configLoader = $this->configLoader;
        $diContainerFactory = $this->diContainerFactory;

        $config = $configLoader();

        /* @var $diContainer ContainerInterface */
        $diContainer = $diContainerFactory($config);

        if ($diContainer->has(RunnerInterface::class)) {
            $diContainer->get(RunnerInterface::class)->register();
        }

        return $diContainer;
    }
}
