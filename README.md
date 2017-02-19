# Phoundation

[![Build Status](https://travis-ci.org/nikolaposa/phoundation.svg?branch=master)](https://travis-ci.org/nikolaposa/phoundation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/phoundation/v/stable)](https://packagist.org/packages/nikolaposa/phoundation)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

Phoundation (pronounced the same way as foundation) is a framework-agnostic basis for bootstrapping PHP applications. Provides components that facilitate tasks such as configuration loading, DI container initialization and registering central error handler.

Used as a core dependency of the PHP Application Skeleton project.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/phoundation
```

## Purpose

Nowadays PHP-based applications typically follow this flow in their main entry point:

```
require '../vendor/autoload.php';

$config = require '../config/config.php';

$diContainer = new \Some\Di\Container($config);

$app = $diContainer->get('App');
$app->run();
```

Part of loading configuration files and initializing DI container are common characteristics for most of them. Regardless of the framework that is being used bootstrapping principle is the same, but concrete implementation can vary in terms of syntax, naming, and similar. And this is exactly the problem from the reusability and interoperability perspective.

Phoundation aims to abstract and facilitate implementation of these matters through several independent components, still integrated together through a bootstrapping mechanism suitable for virtually any type of PHP project. 

### Configuration files

Besides infrastructure settings, for example database connection, cache, email server, etc., configuration files usually contain application-specific, like definitions of services that should be registered with the DI container and similar. Configuration values are different depending on the environment in which application is running (development, production), and there are multiple strategies for having per-environment configuration setup. 

Ultimatelly, load configuration based on environment, and make it available for the application typically in form of an array or an ArrayObject.

While Phoundation is fully flexible in this regard, 

### Dependency Injection

DI container which is the output of the bootstrapping procedure, and every application service starting from the application runner itself is defined in it.

Phoundation provides factories for several DI container solutions:

- [Zend Service Manager](https://github.com/zendframework/zend-servicemanager)
- [Aura.DI](https://github.com/auraphp/Aura.Di)
- [Pimple](http://pimple.sensiolabs.org/)

### Central error handler

While this one is only an addition, sooner or later a mechanism for handling fatal errors and uncaught exceptions will be needed. Phoundation attempts to solve this via `Phoundation\ErrorHandling\RunnerInterface` abstraction, whereas if defined in the DI container, it will automatically be registered during bootstrap. Concrete implementation based on a very popular [Whoops](https://github.com/filp/whoops) error handler is also available.

### Bootstrap

Component that connects these different pieces together is `Bootstrap` which is in fact how Phoundation is meant to be used.

## Usage

```php

use Phoundation\Bootstrap\Bootstrap;
use Phoundation\Config\Loader\GlobConfigLoader;
use Phoundation\Di\Container\Factory\ZendServiceManagerFactory;

$env = getenv('APP_ENV') ?: 'local';
$configGlobPath = sprintf('config/autoload/{{,*.}global,{,*.}%s}.php', $env);

$bootstrap = new Bootstrap(
    new GlobConfigLoader($configGlobPath),
    new ZendServiceManagerFactory()
);

$diContainer = $bootstrap();

//...
```

## Author

**Nikola Poša**

* https://twitter.com/nikolaposa
* https://github.com/nikolaposa

## Copyright and license

Copyright 2017 Nikola Poša. Released under MIT License - see the `LICENSE` file for details.
