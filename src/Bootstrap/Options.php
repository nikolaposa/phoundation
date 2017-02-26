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

namespace Phoundation\Bootstrap;

use Phoundation\Di\Container\Factory\FactoryInterface;
use Phoundation\Exception\InvalidBootstrapOptionsException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Options
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $configPaths;

    /**
     * @var array
     */
    protected $configGlobPaths;

    /**
     * @var callable
     */
    protected $diContainerFactory;

    /**
     * @var string
     */
    protected $diConfigKey;

    /**
     * @var string
     */
    protected $configServiceName;

    protected function __construct(
        array $config,
        array $configPaths,
        array $configGlobPaths,
        callable $diContainerFactory,
        string $diConfigKey,
        string $configServiceName
    ) {
        $this->config = $config;
        $this->configPaths = $configPaths;
        $this->configGlobPaths = $configGlobPaths;
        $this->diContainerFactory = $diContainerFactory;
        $this->diConfigKey = $diConfigKey;
        $this->configServiceName = $configServiceName;
    }

    public static function fromArray(array $options)
    {
        $options = array_merge([
            'config' => [],
            'config_paths' => [],
            'config_glob_paths' => [],
            'config_provider' => [],
            'di_config_key' => FactoryInterface::DEFAULT_DI_CONFIG_KEY,
            'config_service_name' => FactoryInterface::DEFAULT_CONFIG_SERVICE_NAME,
        ], $options);

        if (!isset($options['di_container_factory'])) {
            throw InvalidBootstrapOptionsException::forMissingDiContainerFactory();
        } elseif (is_string($options['di_container_factory'])) {
            $options['di_container_factory'] = new $options['di_container_factory']();
        }

        return new self(
            $options['config'],
            $options['config_paths'],
            $options['config_glob_paths'],
            $options['di_container_factory'],
            $options['di_config_key'],
            $options['config_service_name']
        );
    }

    public function getConfig() : array
    {
        return $this->config;
    }

    public function getConfigPaths() : array
    {
        return $this->configPaths;
    }

    public function getConfigGlobPaths() : array
    {
        return $this->configGlobPaths;
    }

    public function getDiContainerFactory() : callable
    {
        return $this->diContainerFactory;
    }

    public function getDiConfigKey() : string
    {
        return $this->diConfigKey;
    }

    public function getConfigServiceName() : string
    {
        return $this->configServiceName;
    }
}
