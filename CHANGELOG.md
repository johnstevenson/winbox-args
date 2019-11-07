## [Unreleased]

## [1.1.1] - 2019-11-07
  * Fixed: platform inconsistencies when escaping unexpected `false` or `null` values.

## [1.1.0] - 2018-08-14
  * Added: `escapeCommand` method, taking an array of arguments that includes the executable.
  * Added: optional `module` argument to improve edge-case escaping for the executable.
  * Fixed: replaced `escapeshellarg` usage to avoid locale problems.
  * Fixed: continual updates to use Chocolatey with appveyor.

## [1.0.0] - 2016-08-04
  * Initial release

[Unreleased]: https://github.com/johnstevenson/winbox-args/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/johnstevenson/winbox-args/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/johnstevenson/winbox-args/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/johnstevenson/winbox-args/compare/a6a5783f708a...v1.0.0
