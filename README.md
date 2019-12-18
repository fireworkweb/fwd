# fireworkweb/fwd

[![Build Status](https://travis-ci.com/fireworkweb/fwd.svg?branch=php)](https://travis-ci.com/fireworkweb/fwd)
[![codecov](https://codecov.io/gh/fireworkweb/fwd/branch/php/graph/badge.svg)](https://codecov.io/gh/fireworkweb/fwd)

## Installation

You need to have PHP 7.2+ installed in order to run `fwd`.

```bash
curl -L https://github.com/fireworkweb/fwd/raw/php/builds/fwd -o /usr/local/bin/fwd
chmod +x /usr/local/bin/fwd
```

* For Release Candidate and testing the latest features please checkout https://github.com/fireworkweb/fwd/issues/70#issuecomment-554606414.
* Don't have and don't want to have PHP installed locally? Checkout and follow https://github.com/fireworkweb/fwd/issues/21 for 100% Docker usage, thus not requiring PHP.

## Usage

```bash
fwd install
fwd start
fwd composer install
fwd artisan migrate:fresh --seed
fwd yarn install
fwd yarn dev
fwd down
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
