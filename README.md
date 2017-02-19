# Foundation

[![Build Status](https://travis-ci.org/nikolaposa/foundation.svg?branch=master)](https://travis-ci.org/nikolaposa/foundation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nikolaposa/foundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/foundation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nikolaposa/foundation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nikolaposa/foundation/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nikolaposa/foundation/v/stable)](https://packagist.org/packages/nikolaposa/foundation)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

Framework-agnostic basis for bootstrapping PHP applications. Provides components that facilitate tasks such as configuration loading, DI container initialization and registering central error handler.

Used as a core dependency of the PHP Application Skeleton project.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/foundation
```

## Usage

```php

use Foundation\Bootstrap\Bootstrap;
use Foundation\Config\Loader\GlobConfigLoader;
use Foundation\Di\Container\Factory\ZendServiceManagerFactory;

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
