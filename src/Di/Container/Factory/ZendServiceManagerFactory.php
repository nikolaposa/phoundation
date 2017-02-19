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

namespace Phoundation\Di\Container\Factory;

use Zend\ServiceManager\ServiceManager;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
final class ZendServiceManagerFactory extends AbstractFactory
{
    protected function createContainer()
    {
        return new ServiceManager();
    }

    protected function configure($container)
    {
        /* @var $container ServiceManager */

        $container->configure($this->getDiConfig());

        $container->setService($this->getConfigServiceName(), $this->getConfig());
    }
}
