# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.2.0] - 2021-12-22

### Added
- Added the Twig template engine for creating views and configured it to load automatically. See also [1: Add twig templates as views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/1).
- Added an example form example view using Twig under "GET /twigview". See also [1: Add twig templates as views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/1).
- Added Monolog as a logger instance and configured it to output application logs into a file. See also [2: Add monolog as logger instance](https://github.com/Digital-Media/fhooe-router-skeleton/issues/2).
- Added a view for "POST /formresult" using Bootstrap. See also: [3: Use Bootstrap for enhanced views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/3).

### Changed

- Updated to fhooe/router v0.2.0.
- Updated the view for "GET /" using Bootstrap. See also: [3: Use Bootstrap for enhanced views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/3).
- Updated the view for "GET /form" using Bootstrap. See also: [3: Use Bootstrap for enhanced views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/3).
- Updated the 404 view using Bootstrap. See also [3: Use Bootstrap for enhanced views](https://github.com/Digital-Media/fhooe-router-skeleton/issues/3).

## [0.1.0] - 2021-12-16
### Added
- Added an example invocation of the router in `index.php`.
- Added three example views for the main page, a form submission and a 404 page.
- Added a `.htaccess` file which redirects all requests back to `index.php`.
- Set up `composer.json` for the use with [Composer](https://getcomposer.org/) and [Packagist](https://packagist.org/).
- Added [phpstan](https://packagist.org/packages/phpstan/phpstan) for code analysis.
- Added extensive `README.md`.
- Added notes on Contributing.
- Added this changelog.

[Unreleased]: https://github.com/Digital-Media/fhooe-router-skeleton/compare/v0.2.0...HEAD
[0.2.0]: https://github.com/Digital-Media/fhooe-router-skeleton/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/Digital-Media/fhooe-router-skeleton/releases/tag/v0.1.0
