# Changelog

All notable changes to this package are documented here.
The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package uses [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [1.1.0] - 2026-11-21

### Changed
- The licence check follows MageBooster License 1.1: without a valid serial number (missing, invalid, expired, other domain or other product) the module keeps working for 30 days, then scheduled and manual TCMB rate imports stop with a "licence required" message; existing rates stay as they are. Development hosts (localhost, *.localhost, *.test, *.local, 127.0.0.1) always pass the domain check. The admin shows the state on every page; nothing is shown on the storefront and nothing is sent anywhere.
- Requires magebooster/module-license ^1.1.

## [1.0.0] - 2026-11-20

First public release.

### Changed
- PHP 8.0 compatibility: readonly properties removed, `php: >=8.0` (was `^8.3`).
- Commercial MageBooster licence (WebWorks 101 OÜ) replaces the previous licence; `LICENSE.txt`, composer `license: proprietary`, copyright and licence notice in every source file.
- Feed error notice text is escaped with `ENT_QUOTES | ENT_SUBSTITUTE`; the notice id uses sha256 instead of md5.

### Added
- Composer metadata for the public repository (homepage, authors, support, keywords), README header, guides in `docs/`, `.gitignore`.

## [0.1.0] - 2026-10-21

### Added
- Initial MageBooster_CurrencyRates module: TCMB rates as a Magento currency import service, with an optional markup.
