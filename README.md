Winbox-Args
===========

[![Build Status](https://travis-ci.org/johnstevenson/winbox-args.svg?branch=master)](https://travis-ci.org/johnstevenson/winbox-args)
[![Build status](https://ci.appveyor.com/api/projects/status/p4k75qqcyioj0mfl?svg=true)](https://ci.appveyor.com/project/johnstevenson/winbox-args)

A PHP function to escape command-line arguments on Windows.

## Contents
* [About](#About)
* [Installation](#Installation)
* [Usage](#Usage)
* [Is that it?](#Why)
* [License](#License)

<a name="About"></a>
## About
The native `escapeshellarg` function provides a convenient way to ...

<a name="Installation"></a>
## Installation
The easiest way is using [Packagist][packagist] and [composer][composer]. Or you can [download][download] and extract it then point a PSR-4 autoloader to the `src` directory.

<a name="Usage"></a>
## Usage
The function is presented as a static class method:

```php
$escaped = Winbox\Args::escape($argument);
```

Alternatively, you can just copy the function and use it however you wish (please follow the attribution guidelines in the function comments).

<a name="Why"></a>
## Is that it?
Yup. An entire repo for a tiny function. Why you might need to use it, and a full explanation of how Windows performs command-line parsing, can be found in the [Wiki][wiki].

<a name="License"></a>
## License

Winbox-Args is licensed under the MIT License - see the LICENSE file for details.

[composer]: http://getcomposer.org
[packagist]: https://packagist.org/packages/winbox/args
[download]: https://github.com/johnstevenson/winbox-args/archive/master.zip
[wiki]:https://github.com/johnstevenson/winbox-args/wiki/Home

