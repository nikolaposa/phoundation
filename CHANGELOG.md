# Change Log

All notable changes to this project will be documented in this file.

## 3.0.0 - 2020-03-28

### Changed
- PHP 7.2 is now the minimum required version
- Renamed `ConfigLoaderInterface` to `ConfigLoader`
- Renamed `Di\DiContainerFactoryInterface` to `DependencyInjection\DiContainerFactory`
- Moved `Bootstrap` class into top-level (`Phoundation`) namespace
- Renamed `ConfigLoader->_invoke()` to `ConfigLoader->load()`
- Renamed `DiContainerFactory->_invoke()` to `DiContainerFactory->create()`
- `ConfigLoader` returns internal `array` type

### Removed
- `Config` class
- Dropped out-of-the-box support for most of DI Containers to encourage custom 
implementations in user land code

## 2.0.0 - 2017-06-25

### Changed
- [2: Strict return types](https://github.com/nikolaposa/phoundation/pull/2)
- [3: Using PSR-11 container interface instead of container-interop](https://github.com/nikolaposa/phoundation/pull/3)

## 1.1.0 - 2017-06-14

### Added
- `ConfigLoaderAggregate` that aggregates configurations from multiple loaders.

## 1.0.0 - 2017-03-04

### Added
- `CachingConfigLoader` that caches loaded configurations.

### Changed
- `Interop\Container\ContainerInterface` as return type declaration in DI container factories


[link-unreleased]: https://github.com/nikolaposa/phoundation/compare/2.0.0...HEAD
