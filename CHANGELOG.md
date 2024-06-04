# Changelog

All notable changes to `laravel-csp` will be documented in this file

## 2.10.0 - 2024-06-04

### What's Changed

* feat: added missing 'wasm-unsafe-eval' keyword by @fxnm in https://github.com/spatie/laravel-csp/pull/125

### New Contributors

* @fxnm made their first contribution in https://github.com/spatie/laravel-csp/pull/125

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.9.0...2.10.0

## 2.9.0 - 2024-02-27

### What's Changed

* Fix incorrect namespaces in README.md by @dnwjn in https://github.com/spatie/laravel-csp/pull/121
* chore: prepare for laravel 11 by @joostdebruijn in https://github.com/spatie/laravel-csp/pull/124

### New Contributors

* @dnwjn made their first contribution in https://github.com/spatie/laravel-csp/pull/121
* @joostdebruijn made their first contribution in https://github.com/spatie/laravel-csp/pull/124

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.8.4...2.9.0

## 2.8.4 - 2024-01-05

### What's Changed

* Removed expired link from readme file by @DevYunus in https://github.com/spatie/laravel-csp/pull/118
* Add require-trusted-types-for directive by @nicolasbeauvais in https://github.com/spatie/laravel-csp/pull/120

### New Contributors

* @DevYunus made their first contribution in https://github.com/spatie/laravel-csp/pull/118
* @nicolasbeauvais made their first contribution in https://github.com/spatie/laravel-csp/pull/120

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.8.3...2.8.4

## 2.8.3 - 2023-02-13

### What's Changed

- Add PHP 8.2 Support by @patinthehat in https://github.com/spatie/laravel-csp/pull/109
- Improve grammar and formatting of readme by @rubenvanerk in https://github.com/spatie/laravel-csp/pull/112
- fix github test badge by @askdkc in https://github.com/spatie/laravel-csp/pull/113

### New Contributors

- @patinthehat made their first contribution in https://github.com/spatie/laravel-csp/pull/109
- @rubenvanerk made their first contribution in https://github.com/spatie/laravel-csp/pull/112
- @askdkc made their first contribution in https://github.com/spatie/laravel-csp/pull/113

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.8.2...2.8.3

## 2.8.2 - 2022-09-13

### What's Changed

- Add wss: (secure websocket) scheme support by @bitwise-operators in https://github.com/spatie/laravel-csp/pull/105

### New Contributors

- @bitwise-operators made their first contribution in https://github.com/spatie/laravel-csp/pull/105

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.8.1...2.8.2

## 2.8.1 - 2022-09-01

### What's Changed

- Lazily register blade directives by @axlon in https://github.com/spatie/laravel-csp/pull/104

### New Contributors

- @axlon made their first contribution in https://github.com/spatie/laravel-csp/pull/104

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.8.0...2.8.1

## 2.8.0 - 2022-08-25

### What's Changed

- Fixing readme issues by @lukeclifton in https://github.com/spatie/laravel-csp/pull/88
- Update README.md by @melicerte in https://github.com/spatie/laravel-csp/pull/90
- Update README.md by @melicerte in https://github.com/spatie/laravel-csp/pull/91
- Rewrite tests to use Pest by @Magiczne in https://github.com/spatie/laravel-csp/pull/92
- Wrong method name for Vite nonce by @rcerljenko in https://github.com/spatie/laravel-csp/pull/102
- Adds a new `@cspMetaTag` blade directive by @lukeraymonddowning in https://github.com/spatie/laravel-csp/pull/103

### New Contributors

- @lukeclifton made their first contribution in https://github.com/spatie/laravel-csp/pull/88
- @melicerte made their first contribution in https://github.com/spatie/laravel-csp/pull/90
- @Magiczne made their first contribution in https://github.com/spatie/laravel-csp/pull/92
- @rcerljenko made their first contribution in https://github.com/spatie/laravel-csp/pull/102
- @lukeraymonddowning made their first contribution in https://github.com/spatie/laravel-csp/pull/103

**Full Changelog**: https://github.com/spatie/laravel-csp/compare/2.7.0...2.8.0

## 2.7.0 - 2022-01-13

- support Laravel 9

## 2.6.4 - 2020-12-01

- add support for PHP 8

## 2.6.3 - 2020-10-21

- add prefetch-src directive (#77)

## 2.6.2 - 2020-10-01

- add unsafe-hashes keyword

## 2.6.1 - 2020-09-09

- Add support for Laravel 8

## 2.6.0 - 2020-07-17

- added "report-to" directive (#71)

## 2.5.1 - 2020-03-06

- add websockets scheme support (#65)

## 2.5.0 - 2020-03-03

- Add Laravel 7 support
- Add None override

## 2.4.0 - 2019-09-04

- Add Laravel 6.0 support

## 2.3.0 - 2019-05-23

- add webrtc-src directive

## 2.2.0 - 2019-02-27

- drop support for Laravel 5.7 and below
- drop support for PHP 7.1 and below

## 2.1.3 - 2019-02-27

- add support for Laravel 5.8

## 2.1.2 - 2019-02-04

- replace str_random and starts_with helpers to its class based counterpart

## 2.1.1 - 2019-02-01

- use Arr:: and Str:: functions

## 2.1.0 - 2018-11-12

- add new script and style directives

## 2.0.0 - 2018-11-05

- moved most values in `Value` to `Keyword`
- remove deprecated `cspValue` function

## 1.4.0 - 2018-10-30

- add possibility to change value to no value

## 1.3.3 - 2018-09-04

- support for Laravel 5.7

## 1.3.2 - 2018-08-02

- fix compatibility with Lumen

## 1.3.1 - 2018-08-02

- make compatible with Lumen

## 1.3.0 - 2018-07-27

- add value constant for `data:`

## 1.2.2 - 2018-05-05

- fix blade directive

## 1.2.1 - 2018-04-30

- improvements around quoting values

## 1.2.0 - 2018-04-13

- add `csp_nonce` function

## 1.1.0 - 2018-04-13

- add value constants

## 1.0.5 - 2018-03-08

- add support for PHP 7.0

## 1.0.4 - 2018-03-01

- fix blade directive

## 1.0.3 - 2018-02-21

- improve base profile

## 1.0.2 - 2018-02-20

- fix directory name

## 1.0.1 - 2018-02-20

- fix naming of classes

## 1.0.0 - 2018-02-20

**BROKEN VERSION, DO NOT USE**

- initial release
