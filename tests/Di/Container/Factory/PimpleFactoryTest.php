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

namespace Phoundation\Tests\Di\Container\Factory;

use Phoundation\Config\Config;
use Phoundation\Di\Container\Factory\PimpleFactory;
use Phoundation\Tests\TestAsset\Service\InMemoryLogger;
use Phoundation\Tests\TestAsset\Service\InMemoryLoggerFactory;
use Psr\Container\ContainerInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PimpleFactoryTest extends ContainerFactoryTest
{
    protected function createFactory(array $options = [])
    {
        return new PimpleFactory($options);
    }

    /**
     * @test
     */
    public function it_sets_factory_as_service_after_initial_service_creation()
    {
        $factory = $this->createFactory();
        $config = Config::fromArray([
            'di' => [
                'factories' => [
                    'logger' => InMemoryLoggerFactory::class,
                ],
            ],
        ]);

        /**
         * @var $container ContainerInterface
         */
        $container = $factory($config);

        $container->get('logger');

        $this->assertTrue($container->has(InMemoryLoggerFactory::class));

        $this->assertInstanceOf(InMemoryLogger::class, $container->get('logger'));
    }
}
