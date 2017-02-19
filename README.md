# Phoundation

[![Build Status](https://travis-ci.org/nikolaposa/phoundation.svg?branch=master)](https://travis-ci.org/nikolaposa/phoundation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/phoundation/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/phoundation/v/stable)](https://packagist.org/packages/nikolaposa/phoundation)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

Framework-agnostic basis for bootstrapping PHP applications. Provides components that facilitate tasks such as configuration loading, DI container initialization and registering central error handler.

Used as a core dependency of the PHP Application Skeleton project.

## Purpose

Most PHP-based applications share some common characteristics:

- **configuration files** - managing environment-specific configurations
- **dependency injection** - defining application services and adding them to the DI container
- **central error handler** - registering global error handler for catching fatal errors and uncaught exceptions

Phoundation aims to abstract and facilitate implementation of these matters through several independent components, still integrated together through a bootstrapping mechanism suitable for virtually any project regardless of the framework that is being used. 

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/phoundation
```

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
