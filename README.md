# Phoundation

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

Phoundation (pronounced the same way as "foundation") facilitates the routine step of 
bootstrapping PHP applications.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following 
command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require nikolaposa/phoundation
```

## Purpose

Bootstrapping of today's PHP applications typically comes down to:

1. Configuration loading
1. Dependency Injection Container initialization

Phoundation aims to reduce the amount of repetitive code needed for the application startup logic 
by abstracting bootstrapping process. 


## Usage

Given the configuration files:

config/global.php

```php
return [
    'db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'secret',
        'dbname' => 'test',
    ],
    'dependencies' => [
        'factories' => [
            \PDO::class => function () {
                return new \PDO('sqlite::memory:');
            },
            'My\\Web\\Application' => My\Web\ApplicationFactory::class,
        ]
    ],
];
```

config/local.php

```php
return [
    'db' => [
        'user' => 'admin',
        'password' => '1234',
    ],
];
```

Create bootstrap script which typically lives in `src/bootstrap.php`: 


```php

use Phoundation\Bootstrap;
use Phoundation\Config\FileConfigLoader;
use Phoundation\DependencyInjection\LaminasServiceManagerFactory;

$bootstrap = new Bootstrap(
    new FileConfigLoader(glob(sprintf('config/{{,*.}global,{,*.}%s}.php', getenv('APP_ENV') ?: 'local'), GLOB_BRACE)),
    new LaminasServiceManagerFactory()
);

return $bootstrap();
```

Load bootstrap in your web application root (for example `public/index.php`):

```php

/* @var $diContainer \Psr\Container\ContainerInterface */
$diContainer = require  __DIR__ . '/../src/bootstrap.php';

$diContainer->get('My\\Web\\Application')->run();
```

## Credits

- [Nikola Poša][link-author]
- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://poser.pugx.org/nikolaposa/phoundation/v/stable
[ico-build]: https://travis-ci.com/nikolaposa/phoundation.svg?branch=master
[ico-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/coverage.png?b=master
[ico-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/phoundation/badges/quality-score.png?b=master
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/nikolaposa/phoundation
[link-build]: https://travis-ci.com/nikolaposa/phoundation
[link-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/phoundation/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/phoundation
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/nikolaposa
[link-contributors]: ../../contributors
