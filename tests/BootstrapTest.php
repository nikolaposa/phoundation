<?php

declare(strict_types=1);

namespace Phoundation\Tests;

use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Phoundation\Bootstrap;
use Phoundation\Config\StaticConfigLoader;
use Phoundation\DependencyInjection\LaminasServiceManagerFactory;

class BootstrapTest extends TestCase
{
    /**
     * @test
     */
    public function it_produces_di_container(): void
    {
        $bootstrap = new Bootstrap(
            new StaticConfigLoader([
                'foo' => 'bar',
            ]),
            new LaminasServiceManagerFactory()
        );

        $diContainer = $bootstrap();

        $this->assertInstanceOf(ServiceManager::class, $diContainer);
    }
}
