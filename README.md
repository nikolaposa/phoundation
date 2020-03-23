# Phoundation

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

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

Besides environment stack parameters, for example database connection, cache, email server, configuration files usually contain application-specific settings, such as definitions of objects that should be registered with the DI container and similar. Configuration values are different depending on the environment in which application is running (development, production), and there are multiple strategies for having per-environment configuration setup. 

Ultimately, goal is to load/aggregate configurations based on the environment, and make it available for the application typically in form of an array or an ArrayObject.

While Phoundation is quite flexible in this regard thanks to `Phoundation\Config\Loader\ConfigLoaderInterface`, there's a trend adopted by many frameworks and projects for environment-specific configurations, a concept that is nicely described in [Zend Framework's documentation](https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration).

### Dependency Injection

Dependency Injection container is the key surrounding concept that manages logic for constructing and assembling building blocks of the application. Every application service, including application runner itself is defined in it.

Out of the box, Phoundation provides factories for several PSR-11 compliant DI container solutions:

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

See [more examples][link-examples].

## Credits

- [Nikola Poša][link-author]
- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://img.shields.io/packagist/v/nikolaposa/phoundation.svg
[ico-build]: https://travis-ci.com/nikolaposa/phoundation.svg?branch=master
[ico-code-coverage]: https://img.shields.io/scrutinizer/coverage/g/nikolaposa/phoundation.svg?b=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nikolaposa/phoundation.svg?b=master
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/nikolaposa/phoundation
[link-build]: https://travis-ci.com/nikolaposa/phoundation
[link-code-coverage]: https://scrutinizer-ci.com/g/nikolaposa/phoundation/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nikolaposa/phoundation
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/nikolaposa
[link-contributors]: ../../contributors
[link-examples]: examples
