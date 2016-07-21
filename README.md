Winbox-Args
===========

[![Build Status](https://travis-ci.org/johnstevenson/winbox-args.svg?branch=master)](https://travis-ci.org/johnstevenson/winbox-args)
[![Build status](https://ci.appveyor.com/api/projects/status/p4k75qqcyioj0mfl?svg=true)](https://ci.appveyor.com/project/johnstevenson/winbox-args)

A PHP function to escape command-line arguments. On Windows `escapeshellarg` is replaced with a more robust method. Install from [Packagist][packagist] and use it like this:

```php
$escaped = Winbox\Args::escapeArgument($argument);
```

Alternatively, you can just [copy it][function] into your own project (but please keep the license attribution).

### Is that it?
Yup. An entire repo for a tiny function. However, it needs quite a lot of explanation because:

- command-line parsing in Windows is not immediately obvious.
- PHP uses *cmd.exe* to invoke programs when you call `exec`, `system` or `passthru`.
- *cmd.exe* applies its own rules to create a command-line that is then parsed by the receiving executable.
- there is no simple, single solution.

Full details explaining the different parsing rules, potential pitfalls and limitations can be found in the [Wiki][wiki].

## License
Winbox-Args is licensed under the MIT License - see the LICENSE file for details.

[function]: https://github.com/johnstevenson/winbox-args/blob/master/src/Args.php
[wiki]:https://github.com/johnstevenson/winbox-args/wiki/Home
[packagist]: https://packagist.org/packages/winbox/args
