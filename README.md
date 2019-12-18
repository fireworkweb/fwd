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
# to install docker-compose.yml file into the project (just the first time)
fwd install

# start up the application containers
fwd start

# bring down the containers
fwd down

# other commands
fwd composer install
fwd artisan migrate:fresh --seed
fwd yarn install
fwd yarn dev
```

## Environment variables

`fwd` is very flexible for you to tweak and change behaviors using environment variables. Variables like `FWD_HTTP_PORT` that holds the port `http` service will bind to, for example, can easily be changed in a number of ways, find the one that best fits your needs.

The precedence is as follows (from the highest to the lowest precedence):

1. In-line/exported environment variables. (i.e `FWD_DEBUG=1 fwd ...`)
2. `.env` file in the current working directory (dotenv file format; usually not versioned in your project).
3. `.fwd` file in the current working directory (this file holds project specific `fwd` settings that usually are versioned with the project).
4. `$HOME/.fwd` file; so you can have user-system specific changes easily across the board if needed.
5. Defaults `.fwd` distributed file bundled in `fwd` CLI; subject to changes with new versions.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
