# Phoundation

[![Build Status](https://travis-ci.org/nikolaposa/phoundation.svg?branch=master)](https://travis-ci.org/nikolaposa/phoundation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/phoundation/v/stable)](https://packagist.org/packages/nikolaposa/phoundation)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

Phoundation (pronounced the same way as foundation) is a framework-agnostic basis for bootstrapping PHP applications. It provides components that facilitate tasks such as configuration loading, DI container initialization and registering central error handler.

Used as a core dependency of the PHP Application Skeleton project.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/phoundation
```

## Purpose

The entire bootstrapping logic of today's PHP applications can be reduced to two things:

1. loading configuration
1. initialization of the DI container

Phoundation aims to abstract and facilitate implementation of these matters through several independent components, still integrated together through a bootstrapping mechanism suitable for virtually any type of PHP project. 

### Configuration files

Besides parameters for supporting services, for example database connection, cache, email server, configuration files usually contain application-specific settings, such as definitions of objects that should be registered with the DI container and similar. Configuration values are different depending on the environment in which application is running (development, production), and there are multiple strategies for having per-environment configuration setup. 

Ultimately, goal is to load/aggregate configurations based on the environment, and make it available for the application typically in form of an array or an ArrayObject.

While Phoundation is quite flexible in this regard thanks to `Phoundation\Config\Loader\ConfigLoaderInterface`, there's a trend adopted by many frameworks and projects for environment-specific configurations, a concept that is nicely described in [Zend Framework's documentation](https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration).

### Dependency Injection

Logic for constructing and assembling application services constitutes DI container, which is the key concept around which everything else revolves. Every application service, including application runner itself is defined in it.

Phoundation provides factories for several DI container solutions:

- [Zend Service Manager](https://github.com/zendframework/zend-servicemanager)
- [Aura.DI](https://github.com/auraphp/Aura.Di)
- [Pimple](http://pimple.sensiolabs.org/)

### Central error handler

While this one is just a nice add-on and is completely optional, sooner or later a mechanism for handling fatal errors and uncaught exceptions will be needed. Phoundation attempts to solve this via `Phoundation\ErrorHandling\RunnerInterface` abstraction, which if defined in the DI container, it will automatically be registered during bootstrap. Concrete implementation based on a very popular [Whoops](https://github.com/filp/whoops) error handler is also available.

### Bootstrap

Component that connects these different pieces together is `Bootstrap` which is in fact how Phoundation is meant to be used.

## Usage

**index.php**

```php
use Phoundation\Bootstrap\Bootstrap;
use Phoundation\Config\Loader\FileConfigLoader;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;

$bootstrap = new Bootstrap(
    new FileConfigLoader(glob(sprintf('config/{{,*.}global,{,*.}%s}.php', getenv('APP_ENV') ?: 'local'), GLOB_BRACE)),
    new ZendServiceManagerFactory()
);
$diContainer = $bootstrap();

//...
```

**config/global.php**

```php
return [
    'db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'secret',
        'dbname' => 'test',
    ],
    'di' => [
        'factories' => [
            Phoundation\ErrorHandling\RunnerInterface::class => function () {
                return new Phoundation\ErrorHandling\WhoopsRunner(new Whoops\Run());
            },
        ]
    ],
];
```

**config/local.php**

```php
return [
    'db' => [
        'user' => 'test',
        'password' => '1234',
    ],
];
```

## Author

**Nikola Poša**

* https://twitter.com/nikolaposa
* https://github.com/nikolaposa

## Copyright and license

Copyright 2017 Nikola Poša. Released under MIT License - see the `LICENSE` file for details.
